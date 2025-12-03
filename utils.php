<?php
function get_all_pages($directory) {
        $files = scandir($directory);
        $pages = [];
        foreach($files as $v) {
                $file_parts = pathinfo($v);
                if(@$file_parts["extension"] == "php") {
                        $pages[] = $v;
                }
        }
        return($pages);
}

?>
