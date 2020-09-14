## Lumen Docker
Lumen Docker image template based on php:7.2-apache

### Instructions

### to build local
```
cd src
composer install
cd ../
docker build -t gandagang-lumen .
docker run -d -p 80:80 --name lumen_docker gandagang-lumen
```

# envoy slack
https://laravel.com/docs/6.x/envoy
https://serversforhackers.com/c/deploying-with-envoy-cast
```
https://www.getenvoy.io/install/envoy/ubuntu/

# docker php
https://docs.gitlab.com/ee/ci/examples/php.html

### Complete docker command list
https://docs.docker.com/engine/reference/commandline/docker/

### Docker compose
https://docs.docker.com/compose/reference/overview/

### to build local using docker-compose
If you want to change port create .env file in same directory and set there

```
docker-compose up -d
```

### build in gitlab CI CD
download this repo and upload as new repo on gitlab.
change .gitlab-ci.yml as you desire.
tag your repo to build docker image

### run docker image from gitlab
Docker image name here
docker build -t registry.gitlab.com/gandagang/gandagang-lumen .
docker push registry.gitlab.com/gandagang/gandagang-lumen
https://gitlab.com/gandagang/gandagang-lumen

```
docker login registry.gitlab.com
docker pull registry.gitlab.com/gandagang/gandagang-lumen:v1.0.0
docker run -d -p 80:80 --name lumen_docker registry.gitlab.com/gandagang/gandagang-lumen:v1.0.0
```

# multipe account gitlab mac
https://coderwall.com/p/7smjkq/multiple-ssh-keys-for-different-accounts-on-github-or-gitlab


# To clear containers:

docker rm -f $(docker ps -a -q)

# To clear images:

docker rmi -f $(docker images -a -q)

# To clear volumes:

docker volume rm $(docker volume ls -q)

# To clear networks:

docker network rm $(docker network ls | tail -n+2 | awk '{if($2 !~ /bridge|none|host/){ print $1 }}')

# deploy production gitlab pipeline
before deploy to prod, user can add merge request, after merge/ approve. reviewer can check pipeline and deploy to prod