## Запуск тестов

### Перед началом запуска тестов
```
docker-compose up -d webdriver
```

### Запуск
```
docker-compose run --rm codeception run acceptance BasketCest:testName --debug
```

### Если больше не хотим запускать тесты
```
docker-compose down
```

## Замечания

### Тесты, требующие авторизацию
Для корректного запуска тестов, которые содержат аннотацию "@before loadCookies":
* необходимо авторизоваться в браузере на сайте citilink.ru 
* при обновлении страницы скопировать отправляемые cookies из Developer Tools -> Network
* создать файл tests/cookies.txt и положить скопированное содержимое в него