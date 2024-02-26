
// сервер websocket
// команда - node ws.server/server.js
let app = require("express")();
let http = require("http").Server(app);
let io = require("socket.io")(http);
http.listen(6001, function () {
  console.log("listening in port 6001");
});




// Redis слушает события Laravel приложения
var Redis = require('ioredis');
// клиент приложения
var redis = new Redis();

// подписаться на события
// * все события
// error - ошибка в базе данных
// count - кол-во подписанных событий этого клиента
redis.psubscribe('*', function(error, count) { });

// всплытие событий от Laravel
// pattern - шаблон события
// channel - канал события Laravel
// message - сообщение события Laravel
redis.on('pmessage', function(pattern, channel, message) {

    parsedMessage = JSON.parse(message);
    //console.log(parsedMessage);

    if (parsedMessage.event.indexOf('add-gamer') !== -1){
        if (typeof parsedMessage.data.gamer.user_id !== 'undefined'){
            io.emit( parsedMessage.event,
                { data: "{user_id:" + parsedMessage.data.gamer.user_id  + ", nickname:" + parsedMessage.data.gamer.user_one.nickname + "}" }
            );
        }
        // для получения Test данных - не обьект user
        else{
            io.emit( parsedMessage.event, message);
        }
    }
    else if (parsedMessage.event.indexOf('license-status') !== -1){
         io.emit( parsedMessage.event,
             { data: "{attraction_id:" + parsedMessage.data.attraction_id  + ", license-status:" + parsedMessage.data.status + "}" }
         );
    }
    else if (parsedMessage.event.indexOf('session-status') !== -1){
        io.emit( parsedMessage.event,
            { data: "{session_id:" + parsedMessage.data.session_id  + ", status:" + parsedMessage.data.status + "}" }
        );
    }
    else if (parsedMessage.event.indexOf('session-transit') !== -1){
        io.emit( parsedMessage.event,
            { data: "{session_id:" + parsedMessage.data.session_id  + ", data:" + parsedMessage.data.data + "}" }
        );
    }
    else if (parsedMessage.event.indexOf('gamer-exit') !== -1){
        io.emit( parsedMessage.event,
            { data: "{session_id:" + parsedMessage.data.session_id  + ", gamer_id:" + parsedMessage.data.gamer_id + "}" }
        );
    }
    else if (parsedMessage.event.indexOf('query_add_gamer') !== -1){
      io.emit( parsedMessage.event,
        { data: "{session_id:" + parsedMessage.data.session_id  + ", gamer_id:" + parsedMessage.data.gamer_id + "}" }
      );
    }
    else if (parsedMessage.event.indexOf('response_add_gamer') !== -1){
      io.emit( parsedMessage.event,
        { data: "{user_is_added:" + parsedMessage.data.user_is_added + "}" }
      );
    }
    else if (parsedMessage.event.indexOf('delete_status') !== -1){
      io.emit( parsedMessage.event,
        { data: "{delete_status:" + parsedMessage.data.delete_status + "}" }
      );
    }
    else if (parsedMessage.event.indexOf('change_image_gamer') !== -1){
      io.emit( parsedMessage.event, {
          data:
            "{gamer_id:" + parsedMessage.data.gamer_id +
            ", select_border:" + parsedMessage.data.select_border +
            ", select_avatar:" + parsedMessage.data.select_avatar + "}"
        }
      );
    }
    else if (parsedMessage.event.indexOf('change_user_nickname') !== -1){
      io.emit( parsedMessage.event, {
          data:
            "{nickname:" + parsedMessage.data.nickname +
            ", user_id:" + parsedMessage.data.user_id + "}"
        }
      );
    }
  console.log(parsedMessage);
});







//io.on('connection', function(socket){

//  io.emit('add-gamer_20', 'test');

//});


//redis.on('pmessage', function(pattern, channel, message) {

//message = JSON.parse(message);

// на канале
//if (channel.indexOf('attraction') !== -1){
// на этом канале - комнате
// в этом событии
//if (message.event.indexOf('add-gamer') !== -1){
//console.log(JSON.stringify(message));

//io.emit(message.event, JSON.stringify(message.data.gamer));

//io.sockets.emit(message.event, JSON.stringify(message.data.gamer));

//}
// console.log(message);
//}

//io.to('attraction_20').emit('add-gamer', 'test');
// });



//io.on("connection", function(socket) {

// принять от клиента пожелание слушать этот канал
//socket.on('subscribe', function (channel) {
//console.log("I want subscribe on = ", channel);
// добавить этому юзеру комнату - канал
//socket.join(channel, function (error) {
// все его комнаты
// console.log(socket.rooms);
//});

//});

// console.log('connected Id = ', socket.id);

// 1 отправить сообщение всем подключившимся
// socket.send(['Message with server']);

// 2 получают сообщения все кроме текущего подключившигося
// socket.broadcast.send("New user");

// 3 подселить юзера в комнату
// socket.join('vip', function (error) {
//     // все его комнаты
//     console.log(socket.rooms);
// });

// 4 отправить всем кто подписался на это событие в клиенте
// socket.on('message', function (data) {
//     // всем кроме текущего
//     socket.broadcast.send(data);
// });

// 5 отловить данные Evant с PHP
//socket.emit("add-gamer", "world");
//});


// ================================
// http.listen(6001, function () {
//     console.log("listening in port 6001");
// });

// конструктор запроса к серверу с другова сайта
// (new io('http://axes.lara:6001')).on('connect', function() {console.log('connect')});
