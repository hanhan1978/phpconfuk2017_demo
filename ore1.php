<?php
$sock = @socket_create_listen(80);
if(!$sock){die("port in use\n");}
$client_sock = socket_accept($sock);
while(true){
    $buf = socket_read($client_sock, 1024);
    if(preg_match('/GET ([^ ]+)/', $buf, $m) && is_file('./htdocs/'.$m[1])){
        $content = file_get_contents('./htdocs/'.$m[1]);
        $res = "HTTP/1.1 200 OK\nContent-Type: text/html\nServer: OrenoServer/0.1\n\n";
        socket_write($client_sock, $res.$content);
    }
    socket_close($client_sock);
    $client_sock = socket_accept($sock);
}
