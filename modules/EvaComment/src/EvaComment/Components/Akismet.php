<?php
namespace Eva\EvaComment\Components;

class Akismet
{
    const API_KEY = '2d58f5bebea0';


    public function commentCheck( $data ) {
        $path = '/1.1/comment-check';
        $response = $this->send($path,$data);
        return $response;
//        if ( 'true' == $response[1] )
//            return true;
//        else
//            return false;
    }

    public function submitSpam( $data ) {
        $path = '/1.1/submit-spam';
        $response = $this->send($path,$data);

        if ( 'Thanks for making the web a better place.' == $response[1] )
            return true;
        else
            return false;
    }

    public function submitHam( $data ) {
        $path = '/1.1/submit-ham';
        $response = $this->send($path,$data);

        if ( 'Thanks for making the web a better place.' == $response[1] )
            return true;
        else
            return false;
    }

    private function send($path,$data) {
        $request = 'blog='. urlencode($data['blog']) .
            '&user_ip='. urlencode($data['user_ip']) .
            '&user_agent='. urlencode($data['user_agent']) .
            '&referrer='. urlencode($data['referrer']) .
            '&permalink='. urlencode($data['permalink']) .
            '&comment_type='. urlencode($data['comment_type']) .
            '&comment_author='. urlencode($data['comment_author']) .
            '&comment_author_email='. urlencode($data['comment_author_email']) .
            '&comment_author_url='. urlencode($data['comment_author_url']) .
            '&comment_content='. urlencode($data['comment_content']);
        $host = $http_host = self::API_KEY.'.rest.akismet.com';
//        $path = '/1.1/comment-check';
        $port = 80;
        $akismet_ua = "WordPress/3.8.1 | Akismet/2.5.9";
        $content_length = strlen( $request );
        $http_request  = "POST $path HTTP/1.0\r\n";
        $http_request .= "Host: $host\r\n";
        $http_request .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $http_request .= "Content-Length: {$content_length}\r\n";
        $http_request .= "User-Agent: {$akismet_ua}\r\n";
        $http_request .= "\r\n";
        $http_request .= $request;
        $response = '';
        if( false != ( $fs = @fsockopen( $http_host, $port, $errno, $errstr, 10 ) ) ) {

            fwrite( $fs, $http_request );

            while ( !feof( $fs ) )
                $response .= fgets( $fs, 1160 ); // One TCP-IP packet
            fclose( $fs );

            $response = explode( "\r\n\r\n", $response, 2 );
        }

        return $response;

    }
}