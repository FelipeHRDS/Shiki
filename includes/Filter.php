<?php

class Filter {
    static function find_customers($vendedorNumber, ...$parameters) {
        global $conn;
    
        $parameter = count($parameters) > 0 ? implode(", ", $parameters) : '*';
    
        $sql = $vendedorNumber != 0 ? "SELECT $parameter FROM users WHERE vendedor = ?;" : "SELECT $parameter FROM users;";
    
        $stmt = null;
        $customers = [];
    
        try {
            $stmt = $conn->prepare($sql);
            
            if ($vendedorNumber != 0) {
                $stmt->bind_param('i', $vendedorNumber);
            }
    
            if ($stmt->execute()) {
                $result = $stmt->get_result();
                if ($result->num_rows > 0) {
                    $customers = $result->fetch_all(MYSQLI_ASSOC);
                } else {
                    return ["error" => "No data found"];
                }
            } else {
                throw new Exception("Unable to execute query");
            }
        } catch (Exception $e) {
            return ["error" => $e->getMessage()];
        } finally {
            if ($stmt !== null) {
                $stmt->close();
            }
        }
        return $customers;
    }

    static function filter_pdf_files_by_date($baseFolderPath, $customers, $targetDate) {
        $subdirectories = glob("$baseFolderPath/*", GLOB_ONLYDIR);
        $filteredFiles = [];
    
        foreach ($subdirectories as $subdirectory) {
            $directoryName = basename($subdirectory);
            
            if (in_array($directoryName, array_column($customers, 'usersUid'))) {
                $pdfFiles = glob("$subdirectory/*.pdf");
    
                usort($pdfFiles, function ($a, $b) {
                    preg_match('/-(\d+)-(\d{2}\.\d{2}\.\d{2})/', basename($a), $matchesA);
                    preg_match('/-(\d+)-(\d{2}\.\d{2}\.\d{2})/', basename($b), $matchesB);
    
                    if (!$matchesA || !$matchesB) {
                        return 0;
                    }
    
                    $numberA = $matchesA[1];
                    $numberB = $matchesB[1];
    
                    return $numberB - $numberA;
                });
    
                foreach ($pdfFiles as $file) {
                    preg_match('/-(\d+)-(\d{2}\.\d{2}\.\d{2})/', basename($file), $matches);
                    
                    if ($matches) {
                        $number = $matches[1];
                        $dateStr = $matches[2];
                        $date = DateTime::createFromFormat('d.m.y', $dateStr);
    
                        // Check if the date is on or after the target date
                        if ($date && $date >= $targetDate) {
                            $filteredFiles[] = [
                                'file' => $file,
                                'number' => $number,
                                'date' => $date
                            ];
                        }
                    }
                }
            }
        }
        return $filteredFiles;
    }
}