<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\EntityManagerInterface;

class DeleteDiscardedBlogPosts extends Command {

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

        parent::__construct();
    }

    protected static $defaultName = 'app:delete-discarded-blog-posts';
    
    protected static string $defaultDescription = 'Check the Database Connection then deletes the post that has a current place = discarded';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        // check DB connection

        $this->entityManager->getConnection()->connect();
        $connected = $this->entityManager->getConnection()->isConnected();

        if(!$connected) {
            
            $output->writeln('Check Database Connection');
        
            return Command::FAILURE;
        }
        // this method must return an integer number with the "exit status code"
        // of the command. You can also use these constants to make code more readable

        // return this if there was no problem running the command
        // (it's equivalent to returning int(0))

        // or return this if some error happened during the execution
        // (it's equivalent to returning int(1))
        // return Command::FAILURE;

        // or return this to indicate incorrect command usage; e.g. invalid options
        // or missing arguments (it's equ   ivalent to returning int(2))
        // return Command::INVALID

        // check result
        $query = $this->entityManager->createQuery(
            'DELETE 
            FROM '. BlogPost::class .' c
            WHERE c.currentState=:state
            '
        )->setParameter('state', 'discarded');
        $query->getResult();
        
        $this->entityManager->flush();

        $output->writeln('Success');

        return Command::SUCCESS;

    }
}