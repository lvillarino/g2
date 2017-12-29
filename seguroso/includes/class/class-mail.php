<?php

/* 
 * Helper for Email content
 */

class G2_Email {
    
    static public function get_subject($type) {
        $subject = get_option('blogname');
        if ($type == 'alert') {
            $subject = 'G2 Alert - ' . get_option('blogname');
        }
        
        return $subject;
    }

        static public function get_header() {
        return ''; // future example: 'content'. "\n\n"
    }
    
    static public function get_footer() {
        return "\n\n" . 'This message was sent automatically from "G2 Security". ' . "\n\n" 
                    . 'Click here if you need Wordpress Support:' . "\n"
                    . 'http://ezosc.com';
    }
}