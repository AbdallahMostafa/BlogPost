<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\BlogPost;

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

        // check result
        $query = $this->entityManager->createQuery(
            'DELETE 
            FROM '. BlogPost::class .' post
            WHERE post.currentState=:state
            '
        )->setParameter('state', 'discarded');
        $query->getResult();
        
        $this->entityManager->flush();

        $output->writeln('Success');

        return Command::SUCCESS;

    }
}