<?php

namespace App\Controllers;

class Read extends BaseController
{
    public function item_query()
    {
        try {
            if (!$this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Invalid request method. Please use AJAX calls only.',
                    'error_type' => 'request_error'
                ]);
            }

            $uid = $this->request->getPost('uid');
            
            if (empty($uid)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'No UID provided in the request',
                    'error_type' => 'validation_error'
                ]);
            }

            $db = \Config\Database::connect();
            
            try {
                $query = $db->query("SELECT item_name, item_type, item_image FROM item_list WHERE item_uid = ?", [$uid]);
                
                if (!$query) {
                    throw new \Exception('Database query failed');
                }

                $result = $query->getRow();

                if ($result) {
                    return $this->response->setJSON([
                        'success' => true,
                        'item' => [
                            'item_uid' => $uid,
                            'item_name' => $result->item_name,
                            'item_type' => $result->item_type,
                            'item_image' => $result->item_image
                        ]
                    ]);
                }

                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Item not found in database',
                    'error_type' => 'not_found',
                    'uid' => $uid
                ]);

            } catch (\Exception $e) {
                log_message('error', 'Database error in item_query: ' . $e->getMessage());
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Database error occurred: ' . $e->getMessage(),
                    'error_type' => 'database_error'
                ]);
            }

        } catch (\Exception $e) {
            log_message('error', 'General error in item_query: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage(),
                'error_type' => 'server_error'
            ]);
        }
    }

    public function item_inspect()
    {
        try {
            if (!$this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Invalid request method. Please use AJAX calls only.',
                    'error_type' => 'request_error'
                ]);
            }

            $uid = $this->request->getPost('uid');
            
            if (empty($uid)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'No UID provided in the request',
                    'error_type' => 'validation_error'
                ]);
            }

            $db = \Config\Database::connect();
            
            try {
                $query = $db->query(
                    "SELECT i.item_name, i.item_type, i.item_image, 
                            COALESCE(insp.inspection_status, 'No Previous Inspection') as last_status,
                            COALESCE(insp.inspection_note, '') as last_notes,
                            COALESCE(insp.inspection_date, '') as last_inspection_date
                     FROM item_list i 
                     LEFT JOIN (
                         SELECT * FROM inspection_data 
                         WHERE item_uid = ? 
                         ORDER BY inspection_date DESC 
                         LIMIT 1
                     ) insp ON i.item_uid = insp.item_uid 
                     WHERE i.item_uid = ?", 
                    [$uid, $uid]
                );
                
                if (!$query) {
                    throw new \Exception('Database query failed');
                }

                $result = $query->getRow();

                if ($result) {
                    return $this->response->setJSON([
                        'success' => true,
                        'item' => [
                            'item_uid' => $uid,
                            'item_name' => $result->item_name,
                            'item_type' => $result->item_type,
                            'item_image' => $result->item_image,
                            'last_status' => $result->last_status,
                            'last_notes' => $result->last_notes,
                            'last_inspection_date' => $result->last_inspection_date
                        ]
                    ]);
                }

                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Item not found in database',
                    'error_type' => 'not_found',
                    'uid' => $uid
                ]);

            } catch (\Exception $e) {
                log_message('error', 'Database error in item_inspect: ' . $e->getMessage());
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Database error occurred: ' . $e->getMessage(),
                    'error_type' => 'database_error'
                ]);
            }

        } catch (\Exception $e) {
            log_message('error', 'General error in item_inspect: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage(),
                'error_type' => 'server_error'
            ]);
        }
    }

    public function save_inspection()
    {
        try {
            if (!$this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Invalid request method. Please use AJAX calls only.',
                    'error_type' => 'request_error'
                ]);
            }

            $session = session();
            $inspector_name = $session->get('inspector_name');

            if (!$inspector_name) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Inspector session not found. Please login again.',
                    'error_type' => 'auth_error'
                ]);
            }

            $uid = $this->request->getPost('uid');
            $status = $this->request->getPost('status');
            $notes = $this->request->getPost('notes');
            
            if (empty($uid) || empty($status)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Missing required fields',
                    'error_type' => 'validation_error'
                ]);
            }

            $db = \Config\Database::connect();
            
            try {
                $data = [
                    'item_uid' => $uid,
                    'inspection_status' => $status,
                    'inspection_note' => $notes,
                    'inspection_date' => date('Y-m-d H:i:s', time() + (7 * 3600)), // Convert to Asia/Jakarta timezone (UTC+7)
                    'inspection_user' => $inspector_name
                ];

                $inserted = $db->table('inspection_data')->insert($data);
                
                if ($inserted) {
                    return $this->response->setJSON([
                        'success' => true,
                        'message' => 'Inspection data saved successfully'
                    ]);
                }

                throw new \Exception('Failed to insert inspection data');

            } catch (\Exception $e) {
                log_message('error', 'Database error in save_inspection: ' . $e->getMessage());
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Database error occurred: ' . $e->getMessage(),
                    'error_type' => 'database_error'
                ]);
            }

        } catch (\Exception $e) {
            log_message('error', 'General error in save_inspection: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage(),
                'error_type' => 'server_error'
            ]);
        }
    }

    public function get_inspection_history()
    {
        try {
            if (!$this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Invalid request method. Please use AJAX calls only.',
                    'error_type' => 'request_error'
                ]);
            }

            $uid = $this->request->getPost('uid');
            
            if (empty($uid)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'No UID provided in the request',
                    'error_type' => 'validation_error'
                ]);
            }

            $db = \Config\Database::connect();
            
            try {
                $query = $db->query(
                    "SELECT inspection_status, inspection_note, inspection_date, inspection_user 
                     FROM inspection_data 
                     WHERE item_uid = ? 
                     ORDER BY inspection_date DESC",
                    [$uid]
                );
                
                if (!$query) {
                    throw new \Exception('Database query failed');
                }

                $results = $query->getResult();

                return $this->response->setJSON([
                    'success' => true,
                    'history' => $results
                ]);

            } catch (\Exception $e) {
                log_message('error', 'Database error in get_inspection_history: ' . $e->getMessage());
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Database error occurred: ' . $e->getMessage(),
                    'error_type' => 'database_error'
                ]);
            }

        } catch (\Exception $e) {
            log_message('error', 'General error in get_inspection_history: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage(),
                'error_type' => 'server_error'
            ]);
        }
    }
}