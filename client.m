function client()

    %host = 'www.swaytech.biz';
    %port = 1024;
    host = '171.217.166.77';
    port = 1701;
    
    obj = tcpip( host, port );
    fopen( obj );
    
    %l_port = get( obj, 'LocalPort' );
    %l_ip = get( obj, 'LocalHost' );
    
    disp( 'get a conn' );
    %[ A, count ] = fread( obj, 1, 'char' );
    %char( A' )
    fwrite( obj, '[123,2,10,12,23,24.5,45.6]' );
    fclose( obj );
  
%     u = udp( host, port, 'LocalPort', l_port, 'LocalHost', l_ip );
%     fopen( u );
%     disp( 'start UDP' );
%     for i=1:4
%         i
%         A = fread( u, 1, 'char' );
%         if isempty(A)
%             pause(0.5);
%         else
%             break;
%         end
%     end
%     char( A' )
%     
%     disp( 'UDP sendto' );
%     fwrite( u, 'ok' );
%     
%     fclose( u );
%     delete( u );
   