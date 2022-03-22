<?php

namespace App\Interfaces;

use App\Entity\BlogPost;

interface BlogPostWorkflowInterface {

    public function changeBlogPostToInReview(BlogPost $blogPost);
    
    public function changeBlogPostToPublished(BlogPost $blogPost);
    
    public function changeBlogPostToDiscarded(BlogPost $blogPost);
}