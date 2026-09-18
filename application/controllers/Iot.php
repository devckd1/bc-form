<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Iot extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->config('iot');
    }

    /**
     * Contoh:
     *
     * Cara 1 (Sensor)
     * /iot/suhu?sensor=RT0202
     *
     * Cara 2 (Filter)
     * /iot/suhu?plant=07&proses=RT&satuan=04
     */
    public function suhu()
    {
        $sensor = $this->input->get('sensor');
        $plant  = $this->input->get('plant');
        $proses = $this->input->get('proses');
        $satuan = $this->input->get('satuan');

        if (empty($sensor) && (empty($plant) || empty($proses) || empty($satuan))) {

            return $this->output
                ->set_status_header(400)
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'success' => false,
                    'message' => 'Parameter sensor atau plant/proses/satuan wajib diisi'
                ]));
        }

        // ==========================
        // Build Query
        // ==========================
        if (!empty($sensor)) {

            $query = [
                'sensor' => $sensor
            ];

        } else {

            $query = [
                'plant'  => $plant,
                'proses' => $proses,
                'satuan' => $satuan
            ];

        }

        $url = $this->config->item('iot_url') . '?' . http_build_query($query);

        $token = $this->config->item('iot_token');

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 10,
            CURLOPT_HTTPHEADER     => [
                'Authorization: Bearer ' . $token,
                'Accept: application/json'
            ]
        ]);

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $error    = curl_error($curl);

        curl_close($curl);

        if ($error) {

            log_message('error', 'IoT CURL Error : ' . $error);

            return $this->output
                ->set_status_header(500)
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'success' => false,
                    'message' => $error
                ]));
        }

        $data = json_decode($response, true);

        if ($httpCode != 200) {

            return $this->output
                ->set_status_header($httpCode)
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'success'  => false,
                    'message'  => 'Sensor tidak merespon',
                    'response' => $data
                ]));
        }

        $nilaiSuhu = '';

        if (is_array($data) && isset($data[0]['value'])) {
            $nilaiSuhu = $data[0]['value'];
        }

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'success' => true,
                'suhu'    => $nilaiSuhu,
                'data'    => $data
            ]));
    }
}
