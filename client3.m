function client3()

    %host = '192.168.1.24';
    host = 'www.swaytech.biz';
    port = 1024;

for j = 1 : 100
    u = udp( host, port );
    fopen( u );    
    fwrite( u, '[123,2,10,12,23,24.5,45.6]' );
    j
    buf = fread( u, 1 );
    char( buf' )
    fclose( u );
    delete( u );   
    pause(1);
end