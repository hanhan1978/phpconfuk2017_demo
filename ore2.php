<?php
$sock = @socket_create_listen(80);
if(!$sock){die("port in use\n");}

$pids=array();
for($i=0; $i < 10 ; $i++){
    $pid = pcntl_fork();
    if($pid){
        $pids[] = $pid;
    }else{
        response($sock);
    }
}
foreach ($pids as $pid) {
    pcntl_waitpid($pid, $status);
}

function response($sock)
{
    $client_sock = socket_accept($sock);
    while (true) {
        $buf = socket_read($client_sock, 1024);
        if (preg_match('/GET ([^ ]+)/', $buf, $m) && is_file('./htdocs/' . $m[1])) {
            $content = file_get_contents('./htdocs/' . $m[1]);
            $res = "HTTP/1.1 200 OK\nContent-Type: text/html\nServer: OrenoServer/0.2\n\n";
            socket_write($client_sock, $res . $content);
        }
        socket_close($client_sock);
        $client_sock = socket_accept($sock);
    }
}
