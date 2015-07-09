function client2()

    host = '192.168.1.24';
    %host = 'www.swaytech.biz';
    port = 1091;

for j = 1 : 400
    u = udp( host, port );
    fopen( u );    
    fwrite( u, 'wdh' );
    
    disp( 'start UDP' );
    for i=1:1
        A = fread( u, 1, 'char' );
        if isempty(A)
            i
            pause(0.5);
        else
            disp( 'UDP sendto' );
            fwrite( u, 'ok' );
            break;
        end
    end
    char( A' )
      
    fclose( u );
    delete( u );
    
    %pause(1);
end