#include <stdio.h>
#include <stdlib.h>
//connect to socket api
#include <sys/types.h>
#include <sys/socket.h>
//store address information
#include <netinet/in.h>

int main()
{
    // create a socket
    int netSocket;
    netSocket = socket(AF_INET, SOCK_STREAM, 0);
    //0 for default protocol,AF_INET for internet socket domain, sock_stream meaning it a tcp connection socket
    // specify an address for the socket
    struct sockaddr_in server_address;
    server_address.sin_family = AF_INET;
    server_address.sin_port = htons(9002);
    server_address.sin_addr.s_addr = INADDR_ANY;

    int connection_status = connect(netSocket, (struct sockaddr *)&server_address, sizeof(server_address));
    if (connection_status == -1)
    {
        printf("There was a problem with the remote server");
    }
    char server_response[256];
    recv(netSocket, &server_response, sizeof(server_response), 0);

    //print the data we recieve from server
    printf("The server sent the data: %s \n", server_response);
    //close the socket
    close(netSocket);
    return 0;
}