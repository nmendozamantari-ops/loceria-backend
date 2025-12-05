<?php

class Model {
    protected $dataFile;

    public function loadJson($filePath) {
        if (!file_exists($filePath)) {
            return [];
        }
        $json = file_get_contents($filePath);
        return json_decode($json, true) ?? [];
    }
}
