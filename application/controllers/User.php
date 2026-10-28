<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('departemen_model');
		$this->load->model('plant_model');
		$this->load->model('pegawai_model');
	}

	private function _map_role($roleName)
	{
		$mapping = [
			'admin' => 0,
			'superadmin' => 9,
			'Manager' => 1,
			'SPV QC' => 2,
			'Produksi' => 3,
			'Forelady' => 8,
			'QC Inspector' => 4
		];

		return $mapping[$roleName] ?? null; 
	}

	public function syncApi()
	{
		header('Content-Type: application/json');

		set_error_handler(function ($severity, $message, $file, $line) {
			throw new ErrorException($message, 0, $severity, $file, $line);
		});

		try {
			$json = file_get_contents('php://input');
			$data = json_decode($json, true);

			if (empty($data['user'])) {
				http_response_code(400);
				echo json_encode([
					'status' => 'error',
					'message' => 'Invalid payload: user missing'
				]);
				return;
			}

			$user = $data['user'];
 
            // Validasi minimal payload
			if (empty($user['uuid']) || empty($user['department']['name']) || empty($user['department']['plant'])) {
				echo json_encode([
					'status' => 'error',
					'message' => 'Missing required user fields'
				]);
				return;
			}

			$departemen = $this->departemen_model->getUuid($user['department']['name']);
			$plant = $this->plant_model->getUuid($user['department']['plant']);

			if (!$departemen || !$plant) {
				echo json_encode([
					'status' => 'error',
					'message' => 'Departemen or Plant not found',
					'departemen' => $user['department']['name'],
					'plant' => $user['department']['plant']
				]);
				return;
			}

			$userData = [
				'uuid' => $user['uuid'],
				'nama' => $user['name'] ?? '',
				'email' => $user['email'] ?? '',
				'username' => $user['username'] ?? '',
				'password' => $user['password'] ?? '',
				'departemen' => $departemen->uuid,
				'activation' => $user['activation'],
				'plant' => $plant->uuid,
				'tipe_user' => $this->_map_role($user['project_role']['role'] ?? '')
			];

			$result = $this->pegawai_model->syncUser($userData);

			if ($result) {
				echo json_encode([
					'status' => 'success',
					'message' => 'User synced successfully'
				]);
			} else {
				$error = $this->db->error();
				echo json_encode([
					'status' => 'error',
					'message' => $error['message'] ?: 'Failed to sync user',
					'details' => $error
				]);
			}

		} catch (Throwable $e) {
			echo json_encode([
				'status' => 'error',
				'message' => $e->getMessage()
			]);
		} finally {
			restore_error_handler();
		}
	}

	public function desyncApi()
	{
		header('Content-Type: application/json');


        // Convert PHP warnings/notices into exceptions
		set_error_handler(function ($severity, $message, $file, $line) {
			throw new ErrorException($message, 0, $severity, $file, $line);
		});


		try {
			$json = file_get_contents('php://input');
			$data = json_decode($json, true);


			if (empty($data['user_uuid'])) {
				echo json_encode([
					'status'  => 'error',
					'message' => 'Invalid payload'
				]);
				return;
			}


			$user = $data['user_uuid'];
			$result = $this->pegawai_model->desyncUser($user);


			if ($result) {
				echo json_encode([
					'status'  => 'success',
					'message' => 'User desynced successfully: ' . $user
				]);
			} else {
				$error = $this->db->error();
				echo json_encode([
					'status'  => 'error',
					'message' => $error['message'] ?: 'Failed to sync user',
					'details' => $error
				]);
			}
		} catch (Throwable $e) {
			echo json_encode([
				'status'  => 'error',
                'message' => $e->getMessage()  // <-- only the PHP error message
            ]);


		} finally {
            // Restore normal error handling
			restore_error_handler();
		}
	}


	public function activation()
	{
		header('Content-Type: application/json');

		set_error_handler(function ($severity, $message, $file, $line) {
			throw new ErrorException($message, 0, $severity, $file, $line);
		});

		try {
			$json = file_get_contents('php://input');
			$data = json_decode($json, true);

			if (empty($data['user']['uuid'])) {
				echo json_encode([
					'status' => 'error',
					'message' => 'Invalid payload: uuid missing'
				]);
				return;
			}

			$result = $this->pegawai_model->activation(['uuid' => $data['user']['uuid']]);

			if ($result) {
				echo json_encode([
					'status' => 'success',
					'message' => 'User Activation Success'
				]);
			} else {
				$error = $this->db->error();
				echo json_encode([
					'status' => 'error',
					'message' => $error['message'] ?: 'Failed to activate user',
					'details' => $error
				]);
			}

		} catch (Throwable $e) {
			echo json_encode([
				'status' => 'error',
				'message' => $e->getMessage()
			]);
		} finally {
			restore_error_handler();
		}
	}

	public function changePassword()
	{
		header('Content-Type: application/json');

		set_error_handler(function ($severity, $message, $file, $line) {
			throw new ErrorException($message, 0, $severity, $file, $line);
		});

		try {
			$json = file_get_contents('php://input');
			$data = json_decode($json, true);
			// var_dump($data['user']);
			// die();
			if (empty($data['user']['uuid']) || empty($data['user']['password'])) {
				http_response_code(400);
				echo json_encode([
					'status' => 'error',
					'message' => 'Invalid payload: uuid or password missing'
				]);
				return;
			}

			$result = $this->pegawai_model->changePasswordAPI([
				'uuid' => $data['user']['uuid'],
				'password' => $data['user']['password']
			]);

			if ($result) {
				echo json_encode([
					'status' => 'success',
					'message' => $result
				]);
			} else {
				$error = $this->db->error();
				echo json_encode([
					'status' => 'error',
					'message' => $error['message'] ?: 'Failed to change password',
					'details' => $error
				]);
			}

		} catch (Throwable $e) {
			echo json_encode([
				'status' => 'error',
				'message' => $e->getMessage()
			]);
		} finally {
			restore_error_handler();
		}
	}
}
