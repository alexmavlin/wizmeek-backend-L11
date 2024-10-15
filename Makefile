up:
	sudo docker-compose up -d
up_build:
	sudo docker-compose up -d --build
down:
	sudo docker-compose down
nginx-reload:
	sudo docker exec nginx nginx -s reload
bash:
	sudo docker exec -it php bash