function udp_test 

echoudp('on',4012)
u = udp('127.0.0.1',4012);
fopen(u)
fwrite(u,65:74)
A = fread(u, 10)'
fclose(u)
delete(u)
echoudp('off')