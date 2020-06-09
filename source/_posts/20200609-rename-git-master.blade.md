---
extends: _layouts.post
title: Renaming your master branch 
author: Stuart Jones
date: 2020-06-09
section: post
tags: [git, development]
image: https://horuskol.net/assets/images/posts/20200522-slow-composer-tortoise.jpg
description: Git lets you rename your branches, but renaming your master branch can be tricky.
canonical_url: https://dev.to/horus_kol/renaming-your-master-branch-2a37
---

<aside>
    This article was originally published at 
    <a class="text-blue-700 visited:text-purple-700 hover:text-indigo-500 underline" href="https://dev.to/horus_kol/renaming-your-master-branch-2a37">Renaming your master branch on dev.to</a>.
</aside>

While it is possible to rename your master branch just like any other branch, there's a few gotchas that you have to look out for.

# Why change?

Language and words have a power and, unfortunately, some words and terminology we have historically used in technology (and in everyday conversation) are not neutral - they have an inadvertent and inherent ability to hurt some sections of our society. Here's an article from the Internet Engineering Task Force (IETF) on [Terminology, Power and Oppressive Language](https://tools.ietf.org/id/draft-knodel-terminology-00.html).

# What name?

Master is the default branch name when initialising a new git repository, and this is expected by many developers. While no branch in git is really special, many workflows rely on the master branch to maintain the thread of development - so any new name needs to convey that same sense of being the centre of the workflow.

I've seen people propose `development` (although, aren't all branches "development"?), `trunk` (a term from SVN, indicating this is what is branched from, but not necessarily intuitive), or `release` (which may not be the case for some repositories). 

The name that seems to be most common for repositories that rename master is `main`. I also think it is neutral and reasonably obvious, so this is the name I decided to use.

# Playing well with others

If you've got a reasonably popular public repository, you might find some people get caught out with the change - so it is probably a good idea to communicate to any collaborators that this change is coming.

# Renaming locally

This is pretty easy to do:

```
git branch -m master newname
```

Done. If you do `git branch` you will see `newname` listed instead of master.

However, this will only work if there is at least one commit (because the master branch isn't actually created until that commit). You can make a simple commit (I usually create a `.gitignore` file as an initial commit), or you can do this:

```
git symbolic-ref HEAD refs/heads/newname
``` 

Change the value of the HEAD reference will have you start on the new default right from the start.

# Renaming a remote master branch

(as a convention, I will use `origin` for the remote repository)

This is where I came a little unstuck - trying to rename a branch hosted on GitLab.

Renaming a remote branch from your local machine requires you to push to the new branch and then remove your old one:

```
git branch -m master newname
git push -u origin newname
git push origin :master
```

`git push -u` tells your local branch to start following the new remote. `git push origin :master` tells the remote repository to remove the master branch there.

However, I got an error like this from my private GitLab server:

```
! [remote rejected] master (pre-receive hook declined)
error: failed to push some refs to 'example.com.au:horuskol/horuskol.net.git'
```

This was because of GitLab managing the default branch and setting some protections on that branch to prevent accidental deletion.

## Removing a master branch from GitLab

First, push the new default to GitLab `git push -u origin newname`.

In GitLab, open the project you want to remove the master branch from.

Click on the project Settings menu and then Repository.

Expand the Default Branch section and change the default branch from the available branches.

Expand the Protected Branches section and remove any protection on master, as well as set any protections you want on the new default branch.

Then you will be able to delete the old remote master with `git push origin :master`.

## Removing a master branch from GitHub

First, push the new default to GitHub `git push -u origin newname`.

Then, in GitHub, open the repository and click on the branches link.

Click on the Change Default Branch button and then select the new default branch.

You will now be able to delete the old remote master with `git push origin :master`.

## Other remotes

I don't have an account with any other git remote repository service (such as BitBucket), so can't provide instruction for those.

# Can we change the default?

It would be great if we could override git's default behaviour when creating a new repository, because we could set this up without ever having to think about it again.

When I tried finding a solution, I hit a lot of commentary about how this is hard-coded into git and found some ugly scripting solutions. However, another member of the Adelaide HeapsGoodDev community, Leigh Brenecki, has a great write up on [changing the default branch for new Git repositories](https://leigh.net.au/writing/git-init-main/).