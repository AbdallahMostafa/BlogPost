# Blog Posts

This repository defines a simple blog application.
As such it has two entities:

* BlogPosts which define the articles which are written
* Comments which allow anyone to comment on existing blog posts.


Listing APIs:

**Listing of posts**
```
GET /blog-posts

[
  {
    "id": 1,
    "name": "hello",
    "content": "wow, this is an amazing blog",
    "currentState": "in_review"
  }
]
```

**Creating new blog posts**
```
POST /blog-posts

{
  "name": "hello world!",
  "content": "Welcome to the blog!",
  "currentState": "in_review"
}
```

**Retrieving a blog post**
```
GET /blog-posts/1

{
  "id": 1,
  "name": "hello",
  "content": "wow, this is an amazing blog",
  "currentState": "in_review"

}
```

**Publish a Post**
```
POST /blog-posts/1/publish
{
  "id": 1,
  "name": "hello",
  "content": "wow, this is an amazing blog",
  "currentState": "in_review"
}
```

**Discard a Post**
```
POST /blog-posts/{id}/discard
{
  "id": 2,
  "name": "hello",
  "content": "wow, this is an amazing blog",
  "currentState": "discarded"
}
```
Similar, for comments there is also an API:

**Listing comments**
```
GET /comments

[
  {
    "id": 1,
    "authorName": "name",
    "authorEmail": "email@example.com",
    "content": "wow, that's an amazong post",
    "blogPost": {
      "id": 1
    }
  }
]
```

**Creating new comments**
```
POST /comments

{
  "authorName": "name",
  "authorEmail": "email@example.com",
  "content": "Great blog!",
  "blogPost": 1
}
```

**Retrieving a comment**
```
GET /comments/2

{
  "id": 2,
  "authorName": "name",
  "authorEmail": "email@example.com",
  "content": "wow, that's an amazong post",
  "blogPost": {
    "id": 1
  }
}
```

