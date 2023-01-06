# Приложение управления Telegram-ботами #

Данное приложение даёт возможность управления ботами. Основано на Laravel с использованием административной панели SleepingOwl Admin(https://sleepingowladmin.ru/). Работа с обновлениями ботов реализована через Long Polling.

## Архитектура ##
### Класс Telegram ##
Путь `/app/Classes/Telegram.php`

Класс для работы с Telegram Api

Методы:
1. `sendMessage` - отправляет сообщение в указанную по id беседу
2. `getUpdate` - возвращает массив обновлений
3. `pushImage` - отправляет картинку в указаную по id беседу

### Класс Manager ##
Путь: `/app/Managers/Abstract/Manager.php`
Абстрактный класс, от которого наследуются менеджеры управления ботами.
Каждый бот должен иметь свой класс наследуемый от Manager.

#### Actions ####
В административной панели есть тип данных `Phrases`, в нём содержатся фразы, на которые боты должны реагировать, в каждой такой фразы есть категория(тип данных `Category`) у которой можно выбрать метод обработки ботом данной фразы. Такие методы обработки объявлятся в классе Manager. Как только боту придёт фраза, выполнится выбранный метод. Название метода обязательно должно содержать слово `Action`(пример `doSomethingAction`).

На данный момент реализованы следующие Actions:
1. `doublePhrasesAction` - собирает рандомную фразу из таблицы `doublePhrases` и отправляет в чат.
2. `jokeAction` - парсит рандомный анекдот с <a href="anekdot.ru">anekdot.ru</a> и отправляет в чат

# Оповещение об ошибках #
Путь: `app/Exceptions/Handler.php`
При возникновении php ошибки будет отправленно сообщение в чат владельца.

# env переменные #
`TELEGRAM_BOT_TOKKEN` - Токен телеграм бота
`TELEGRAM_OWNER_ID` - id владельца бота(туда будут приходить ошибки)
`TELEGRAM_CHAT_ID` - id беседы в которую будет отвечать бот
`UPDATES_TIMEOUT` - задержка ответа на обновления Telegram (long polling)

# Команды artisan
Путь: `routes/console.php`
1. `bot-start` - запускает бота
2. `bot-stop` - останавливает бота

# Дополнительный функционал #
В менеджере текущего бота есть следующий доп функционал:
1. `pushDayImage` - отправляет рандомную картинку из таблицы `day_pictures` исходя из текущего дня недели(у записей есть выбор дня недели)

# Примечание #
Сейчас проект реализован в рамках одного бота, но на его примере можно добавлять сколько угодно ботов и обрабатывать их логику как захочется. Единственное `Actions` в текущей реализации для всех ботов общие, но так как у каждого менеджера метод обработки обновлений(`processUpdate`) прописывается свой, то нет препятствий для разделения `Actions` по конкретным ботам.