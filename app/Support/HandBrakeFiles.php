<?php

namespace App\Support;

use App\Models\FileCompression;

class HandBrakeFiles
{
    public static function getCleanFileName(string $file_name): string
    {
        return preg_replace('/(--[a-z]{3}(?:-[a-z]{3})*)(?=\.\w+$)/', '', $file_name);
    }

    public static function titleFromFileName(FileCompression $file_compression): string
    {
        $clean_name = $file_compression->clean_file_name;

        // If the file name contains season/episode info, strip it and use the remaining name
        if (preg_match('/-s\d{2}e\d{2}/', $file_compression->file_name)) {
            $clean_name = preg_replace('/-s\d{2}e\d{2}(\.\w+)?$/', '', $clean_name);
        } else {
            // Remove the file extension if no season/episode info exists
            $clean_name = preg_replace('/\.\w+$/', '', $clean_name);
        }

        // If season/episode information exists, format the title accordingly
        if (preg_match('/-s(\d{2})e(\d{2})/', $file_compression->file_name, $matches)) {
            $season = $matches[1];
            $episode = $matches[2];
            return sprintf('%s - Season %s Episode %s', $clean_name, $season, $episode);
        }

        // Return the cleaned name for movies without season/episode info
        return $clean_name;
    }
}
