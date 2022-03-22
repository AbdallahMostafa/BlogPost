<?php

namespace App\Service;

use App\Entity\BlogPost;
use App\Interfaces\BlogPostWorkflowInterface;
use Symfony\Component\Workflow\Registry;

class BlogPostService implements BlogPostWorkflowInterface{
    
    private Registry $workflowRegistry;

    public function __construct(Registry $workflowRegistry)
    {
        $this->workflowRegistry = $workflowRegistry;
    }
    function changeBlogPostToInReview(BlogPost $blogPost)
    {
        $workFlow = $this->workflowRegistry->get($blogPost);
        $workFlow->apply($blogPost, 'in_review');
    }
    function changeBlogPostToPublished(BlogPost $blogPost)
    {
        $workFlow = $this->workflowRegistry->get($blogPost);
        $workFlow->apply($blogPost, 'published');
    }
    function changeBlogPostToDiscarded(BlogPost $blogPost)
    {
        $workFlow = $this->workflowRegistry->get($blogPost);
        $workFlow->apply($blogPost, 'discarded');
    }
}