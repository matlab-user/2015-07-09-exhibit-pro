function client4()

    host = '192.168.1.100';
    %host = 'www.swaytech.biz';
    port = 1024;

    u = udp( host, port );
    fopen( u );    
	%fwrite( u, 'N[s001,f,3.6,29]' );
    fwrite( u, 'G[s001]');
    res = fread( u, 100 );
    char( res' )
    fclose( u );
    delete( u );
    return;
    
    fwrite( u, 'I[s002]' );
    disp('send I ok');
    
    fwrite( u, 'S[s002,open]' );
    
    buf = '';
    while 1
        mid = fread( u, 30 );
        if size(mid,1)==0
            break;
        else
            buf = [buf mid'];
            if(mid(end)==']')
                break;
            end
        end
    end
    
    char( buf )
%     [token, remain] = strtok( buf, '[' );
%     [token, remain] = strtok( token, ']' );
%     [token, remain] = strtok( token );
%     [token, remain] = strtok( remain );
%     id = token;
    
    %fwrite( u, strcat('R[s002,',id,',OK]') );
    pause( 4 );
    fwrite( u, 'OK' );
    fclose( u );
    delete( u );
