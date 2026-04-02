FROM nginx:alpine

COPY DevOps/DevOps.Atividades/ /usr/share/nginx/html/

EXPOSE 80

CMD ["nginx", "-g", "daemon off;"]