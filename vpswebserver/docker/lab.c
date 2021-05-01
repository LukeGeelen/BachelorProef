#include <stdio.h>
#include <string.h>
#include <pthread.h>
#include <semaphore.h> 
#include <stdlib.h>
#include <unistd.h>
#include <signal.h>

#define nProducers 4
#define nConsumers 4

#define MAX_COUNT 500

static sem_t obj_produced;
static sem_t obj_consumed;

pthread_mutex_t lock_counter;

int shared_producer_counter;

int isPrime(int n){
    if(n == 1){
        return 0; //assume 1 is not prime
    }
    int dividers = 0;
    for(int i = 2; i <= n/2; i++){
        if(n % i == 0){
            return 0;
        }
    }
    return 1;
}

void* producePrimes(void* input){
    int tid = *(int*) input;
    int localCounter;

    while (shared_producer_counter < MAX_COUNT){
        pthread_mutex_lock(&lock_counter);
        shared_producer_counter++;
        localCounter = shared_producer_counter;
        pthread_mutex_unlock(&lock_counter);

        if(isPrime(localCounter) == 1){
            pthread_mutex_lock(&lock_counter);
            sem_post(&obj_produced);
            sem_wait(&obj_consumed);
            pthread_mutex_unlock(&lock_counter);
        }
    }
}

void* consumer(){
    int prime;
    while(1){
        sem_wait(&obj_produced);
        prime = shared_producer_counter;
        sem_post(&obj_consumed);
        printf("[CONSUMER] %d is prime \n", prime);
    }
}

int main(){
    //create semaphore for producer/consumer comm
    int err;
    err = sem_init(&obj_produced, 0, 0);
    if(err != 0) {
        printf("\ncan't create semaphore: obj_produced [%s]", strerror(err));
        return 1;
    }
    err = sem_init(&obj_consumed, 0, 0);
    if(err != 0) {
        printf("\ncan't create semaphore: obj_produced [%s]", strerror(err));
        return 1;
    }

    //create producers and mutex lock
    pthread_t tidProd[nProducers];
    int idProd[nProducers];    
    pthread_mutex_init(&lock_counter, NULL);
    for(int i=0;i<nProducers;i++){
        idProd[i] = i+1;
        pthread_create(&tidProd[i], NULL, producePrimes, &idProd[i]);
    }

    pthread_t tidCons[nConsumers];
    for(int i=0;i<nConsumers;i++) {
        err = pthread_create(&(tidCons[i]), NULL, &consumer, NULL);
        if (err != 0) {
            printf("\ncan't create consumer thread %d: [%s]", i, strerror(err));
        }
        printf("Consumer thread %d created\n", i);
    }
    

    //wait for producers
    for(int i=0;i<nProducers;i++){
        pthread_join(tidProd[i], NULL);
    }
    // kill consumer threads 
    for(int i=0;i<nConsumers;i++) {
        pthread_kill(tidCons[i], 9);
    }

    // delete the semaphores
    sem_destroy(&obj_produced);
    sem_destroy(&obj_consumed);

    return 0;

}