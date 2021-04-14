//https://stackoverflow.com/questions/6171552/popen-simultaneous-read-and-write

#include<unistd.h>
#include<sys/wait.h>
//#include<sys/prctl.h>
#include<signal.h>
#include<stdlib.h>
#include<string.h>
#include<stdio.h>
#include<json-c/json.h>

#define MAX_INPUT 256

int test(char* input[MAX_INPUT], int rowsInput, char* expectedOutput[MAX_INPUT], int rowsOutput){

  pid_t pid = 0;
  int inpipefd[2];
  int outpipefd[2];
  char buf[256];

  int status;

  pipe(inpipefd);
  pipe(outpipefd);
  pid = fork();
  if (pid == 0)
  {
    // Child
    dup2(outpipefd[0], STDIN_FILENO);
    dup2(inpipefd[1], STDOUT_FILENO);
    dup2(inpipefd[1], STDERR_FILENO);

    //ask kernel to deliver SIGTERM in case the parent dies
    //prctl(PR_SET_PDEATHSIG, SIGTERM);

    //replace tee with your process
    execl("/usr/bin/tee", "herhaalPlus", (char*) NULL);
    // Nothing below this line should be executed by child process. If so, 
    // it means that the execl function wasn't successfull, so lets exit:
    exit(1);
  }
  // The code below will be executed only by parent. You can write and read
  // from the child using pipefd descriptors, and you can send signals to 
  // the process using its pid by kill() function. If the child process will
  // exit unexpectedly, the parent process will obtain SIGCHLD signal that
  // can be handled (e.g. you can respawn the child process).

  //close unused pipe ends
  close(outpipefd[0]);
  close(inpipefd[1]);

  // Now, you can write to outpipefd[1] and read from inpipefd[0] : 
  for(int i = 0; i<rowsInput;i++){
    write(outpipefd[1], input[i], strlen(input[i]));
  }


    while(1){
        printf("reading\n");
        read(inpipefd[0], buf, 256);
         printf("Received answer: %s\n", buf);
         for(int i = 0; i<MAX_INPUT; i++){
        printf("%c ", buf[i]);
    }
    printf("\n");
    }
    

    printf("\n");

     kill(pid, SIGKILL); //send SIGKILL signal to the child process
     waitpid(pid, &status, 0);

    
    /*for(int i = 0; i<7; i++){ //strcmpr debugger
        if(buf[i] == expectedOutput[i]){
            printf("%d character match \n", i);
        } else {
            printf("%d character MISmatch \n", i);
            printf("gotten: %d from test", buf[i]);
            printf("whilst expecting a %d \n", expectedOutput[i]);
        }
    }*/

    return strcmp(buf, expectedOutput[0]);

  

}

int main(int argc, char** argv)
{
    FILE *fp;
	char buffer[1024];
    struct json_object *parsed_json;
    struct json_object *test1;
    struct json_object *input;
    struct json_object *input1;
    fp = fopen("checks.json","r");
    fread(buffer, 1024, 1, fp);
	fclose(fp);

    parsed_json = json_tokener_parse(buffer);

    json_object_object_get_ex(parsed_json, "test1", &test1);
    json_object_object_get_ex(test1, "inputs", &input);
    json_object_object_get_ex(input, "input1", &input1);
    printf("test1, input1: %s\n", json_object_get_string(input1));
    int result;
    //result = test("hello", "hello\x7F");
    char* testInput[] = { "hi" };
    char* eOutput[] = { "hi", "im done"};

    //nRows
    int rowsInput = sizeof(testInput)/sizeof(testInput[0]);
    int rowsOutput = sizeof(eOutput)/sizeof(eOutput[0]);

    printf("it has %d rows \n", rowsInput);
    result = test(testInput, rowsInput, eOutput, rowsOutput);

     printf("monitor terminated, result: %d\n", result);
    return 0;
}