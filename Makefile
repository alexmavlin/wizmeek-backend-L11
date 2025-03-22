up:
	docker-compose up -d
up_build:
	docker-compose up -d --build
down:
	docker-compose down
nginx_reload:
	docker exec nginx nginx -s reload
bash:
	docker exec -it php bash
bash_nginx:
	docker exec -it nginx bash