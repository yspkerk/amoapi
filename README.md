## AmoCRM API Client
Api клиент для работы с amoCRM

## Установка

```
composer require ufee/amoapi
```

## Запуск тестов
Отредактировать vendor/ufee/amoapi/tests/Config.php

```
composer require --dev phpunit/phpunit ^5
vendor/bin/phpunit vendor/ufee/amoapi
```

## Работа с клиентом
Получение объекта для работы с конкретным аккаунтом
```php
$amo = \Ufee\Amo\Amoapi::setInstance([
    'id' => 123,
    'domain' => 'testdomain',
    'login' => 'test@login',
    'hash' => 'testhash',
    'zone' => 'com', // default: ru
    'timezone' => 'Europe/London', // default: Europe/Moscow
    'lang' => 'en' // default: ru
]);
```
Включение логирования заросов (/Logs/m-Y/domain.log)
```php
$amo->queries->logs(true); // to default path
```
или
```php
$amo->queries->logs('path_to_log/queries'); // to custom path
```
Пользовательская отладка запросов 
```php
$amo->queries->listen(function(\Ufee\Amo\Api\Query $query) {
    echo $query->startDate().' - ['.$query->method.'] '.$query->getUrl()."\n";
    print_r($query->headers);
    print_r($query->post_data);
    echo $query->endDate().' - ['.$query->response->getCode().'] '.$query->response->getData()."\n\n";
});
```
## Поиск сущностей
Поиск по дополнительному полю
```php
$leads = $amo->leads()->searchByCustomField('Москва', 'Город');
$companies = $amo->companies()->searchByName('ООО Шарики за Ролики');
$contacts = $amo->contacts()->searchByEmail('Test@Mail.Ru');
$contacts = $amo->contacts()->searchByPhone('89271002030');
```
## Работа с дополнительными полями
Убрать значение
```php
$entity->cf('Имя поля')->reset();
$entity->cf('Организация')->removeBy('name', 'ИП Петров А.А.');
```
Получить значение
```php
$entity->cf('Имя поля')->getValue();
$entity->cf('Имя поля')->getValues();
$entity->cf('Имя поля')->getEnums();
$entity->cf('Дата')->format('Y-m-d');
$entity->cf('Дата')->getTimestamp();
$entity->cf('Организация')->getValues();
```
Задать значение
```php
$entity->cf('Имя поля')->setEnum($enum);
$entity->cf('Имя поля')->setEnums($enums);
$entity->cf('Число')->setValue(5);
$entity->cf('Текст')->setValue('Test');
$entity->cf('Мультисписок')->reset()->setValues(['Мужская одежда', 'Аксессуары']);
$entity->cf('День рождения')->setDate('Y-m-d');
$entity->cf('Дата')->setTimestamp(14867456357);
$entity->cf('Дата')->setDate('Y-m-d');
$entity->cf('Переключатель')->enable();
$entity->cf('Переключатель')->disable();
$entity->cf('Полный адрес')->setCountry('Россия');
$entity->cf('Полный адрес')->setRegion('Чувашская республика');
$entity->cf('Полный адрес')->setCity('Чебоксары');
$entity->cf('Полный адрес')->setIndex(428000);
$entity->cf('Телефон')->setValue('987654321', 'Home');
$entity->cf('Телефон')->setValue('123456789');
$entity->cf('Email')->setValue('best@list.ru');
$entity->cf('Мгн. сообщения')->setValue('bestJa', 'Jabber');
$entity->cf('Юр. лицо')->setName('Команда F5');
$entity->cf('Юр. лицо')->setAddress('РФ, ЧР, г.Чебоксары');
$entity->cf('Юр. лицо')->setType(1);
$entity->cf('Юр. лицо')->setInn(123);
$entity->cf('Юр. лицо')->setKpp(456);
$entity->cf('Организация')->addValue([
    'name' => 'ИП Петров А.А.',
    'city' => 'Москва',
    '...' => '...'
]);
```
## Работа с коллекциями
Перебор, поиск и фильтрация
```php
foreach ($amo->leads as $lead) { ... }
$amo->leads->each(function($lead) { ... });
$leads = $amo->leads->find('name', 'Трубы гофрированные');
$leads = $amo->leads->filter(function($lead) {
    return $lead->sale > 0;
});
$lead = $lead->first();
$lead = $lead->last();
```
Сортировка
```php
$leads->sortBy('sale', 'DESC');
$leads->usort(function($a, $b) {});
$leads->uasort(function($a, $b) {});
```
Дополнительно
```php
$has_contains =  $leads->contains('name', 'Test');
$sale_sum = $leads->sum('sale');
$leads = $leads->transform(function($lead) {
    return [
        'id' => $lead->id,
        'name' => $lead->name
    ];
});
$leads = $leads->toArray();
```
## Работа со сделками
Получение всех сделок
```php
$leads = $amo->leads;
$leads = $amo->leads()->recursiveCall();
$leads = $amo->leads()->call(); // первые 500
```
Получение по дате последнего изменения
```php
$leads = $amo->leads()
             ->modifiedFrom('Y-m-5 09:20:00') // по дате, с 5 числа текущего месяца, с 9:20 утра
             ->modifiedFrom(1528188143) // или по timestamp
             ->maxRows(1000)
             ->listing();
```
Получение по ID
```php
$lead = $amo->leads()->find($id); // array|integer
```
Получение сделок с дополнительным условием
```php
$lead = $amo->leads()->where('key', $val)->recursiveCall();
```
Связанные сущности по сделке 
```php
$contact = $lead->contact;
$contacts = $lead->contacts;
$company = $lead->company;
$tasks = $lead->tasks;
$notes = $lead->notes;
```
Создание сделок
```php
$leads = [
    $amo->leads()->create(),
    $amo->leads()->create()
];
$leads[0]->name = 'Amoapi v7 - 1';
$leads[1]->name = 'Amoapi v7 - 2';
$amo->leads()->add($leads);

$lead = $amo->leads()->create();
$lead->name = 'Amoapi v7';
$lead->attachTag('Amoapi');
$lead->pipeline_id = $amo->account->pipelines->main();
$lead->status_id = $lead->pipeline->statuses->first();
$lead->responsible_user_id = $amo->account->currentUser->id;
$lead->sale = 100500;
$lead->cf('Число')->setValue(5);
$lead->cf('Текст')->setValue('Test');
$lead->cf('Мультисписок')->reset()->setValues(['Мужская одежда', 'Аксессуары']);
$lead->cf('Дата')->setValue(date('Y-m-d'));
$lead->cf('Переключатель')->disable();
$lead->save();
```
Создание сделки из контакта
```php
$lead = $contact->createLead();
$lead->name = 'Amoapi v7';
$lead->save();
```
Копирование сделки
```php
$copy = clone $lead;
$copy->name = 'New lead';
$copy->save();
```

## Работа с контактами
Получение всех контактов
```php
$contacts = $amo->contacts;
$contacts = $amo->contacts()->recursiveCall();
$contacts = $amo->contacts()->call(); // первые 500
```
Получение по ID
```php
$contact = $amo->contacts()->find($id); // array|integer
```
Получение контактов с дополнительным условием
```php
$contacts = $amo->contacts()->where('key', $val)->recursiveCall();
```
Связанные сущности по контакту
```php
$leads = $contact->leads;
$company = $contact->company;
$tasks = $lead->tasks;
$notes = $lead->notes;
```
Создание контактов
```php
$contacts = [
    $amo->contacts()->create(),
    $amo->contacts()->create()
];
$contacts[0]->name = 'Amoapi v7 - 1';
$contacts[1]->name = 'Amoapi v7 - 2';
$amo->contacts()->add($contacts);

$contact = $amo->contacts()->create();
$contact->name = 'Amoapi v7';
$contact->attachTags(['Amoapi', 'Test']);
$contact->cf('Телефон')->setValue('987654321', 'Home');
$contact->cf('Телефон')->setValue('123456789');
$contact->cf('Email')->setValue('best@list.ru');
$contact->save();
```
Создание контакта из сделки
```php
$contact = $lead->createContact();
$contact->name = 'Amoapi v7';
$contact->save();
```
Копирование контакта
```php
$copy = clone $contact;
$copy->name = 'New contact';
$copy->save();
```

## Работа с компаниями
Получение всех компаний
```php
$companies = $amo->companies;
$companies = $amo->companies()->recursiveCall();
$companies = $amo->companies()->call(); // первые 500
```
Получение по ID
```php
$company = $amo->companies()->find($id); // array|integer
```
Получение компаний с дополнительным условием
```php
$companies = $amo->companies()->where('key', $val)->recursiveCall();
```
Связанные сущности по компании
```php
$leads = $company->leads;
$contacts = $company->contacts;
$tasks = $lead->tasks;
$notes = $lead->notes;
```
Создание компаний
```php
$companys = [
    $amo->companies()->create(),
    $amo->companies()->create()
];
$companys[0]->name = 'Amoapi v7 - 1';
$companys[1]->name = 'Amoapi v7 - 2';
$amo->companies()->add($companys);

$company = $amo->companies()->create();
$company->name = 'Amoapi v7';
$company->save();
```
Создание компании из контакта или сделки
```php
$company = $contact->createCompany();
$company = $lead->createCompany();
$company->name = 'Amoapi v7';
$company->save();
```
Копирование компании
```php
$copy = clone $company;
$copy->name = 'New company';
$copy->save();
```
Создать неразобранное
```php
$incoming_lead = $amo->incomingLeads()->create();
$incoming_lead->source_name = 'Binotel source name';
$incoming_lead->source_uid = 'uniq id',
$incoming_lead->addInfo([
            'to' => '901'
            'from' => '0505055050',
            'date_call' => '1554539239',
            'duration' => '50',
            'link' => 'https://my.binotel.ua/?module=cdrs&action=generateFile&fileName=11111111.mp3',
            'service_code' => 'Bonotel',
            'uniq' => 'LJNo328hweidjiln',
            'add_note' => 'Binotel note'
        ])
$company = $amo->companies()->create();
$company->name = 'Amoapi v7';
$incoming_lead->addIE($company);
$incoming_lead->save();
```

## Работа с задачами
Получение всех задач
```php
$tasks = $amo->tasks;
$tasks = $amo->tasks()->recursiveCall();
$tasks = $amo->tasks()->call(); // первые 500
```
Получение по ID
```php
$task = $amo->tasks()->find($id); // array|integer
```
Получение задач с дополнительным условием
```php
$tasks = $amo->tasks()->where('key', $val)->recursiveCall();
```
Создание задач
```php
$tasks = [
    $amo->tasks()->create(),
    $amo->tasks()->create()
];
$tasks[0]->text = 'Amoapi v7 - 1';
$tasks[0]->element_type = 3;
$tasks[0]->element_id = 34762721;
$tasks[1]->text = 'Amoapi v7 - 2';
$tasks[1]->element_type = 2;
$tasks[1]->element_id = 34762720;
$amo->tasks()->add($tasks);

$task = $amo->tasks()->create();
$task->text = 'Amoapi v7';
$task->element_type = 1;
$task->element_id = 34762725;
$task->save();
```
Создание задачи из контакта, сделки или компании
```php
$task = $contact->createTask($type = 1);
$task = $lead->createTask($type = 1);
$task = $company->createTask($type = 1);
$task->text = 'Amoapi v7';
$task->element_type = 1;
$task->element_id = 34762725;
$task->save();
```

## Работа с примечаниями
Получение всех примечаний
```php
$notes = $amo->notes;
$notes = $amo->notes()->where('type', 'contact')->recursiveCall();
$notes = $amo->notes()->where('type', 'lead')->call(); // первые 500
```
Получение по ID
```php
$note = $amo->notes()->find($id); // array|integer
```
Получение примечаний с дополнительным условием
```php
$notes = $amo->notes()->where('key', $val)->recursiveCall();
```
Создание примечаний
```php
$notes = [
    $amo->notes()->create(), 
    $amo->notes()->create()
];
$notes[0]->note_type = 4;
$notes[0]->text = 'Amoapi v7 - 1';
$notes[0]->element_type = 3;
$notes[0]->element_id = 34762721;
$notes[1]->note_type = 4;
$notes[1]->text = 'Amoapi v7 - 2';
$notes[1]->element_type = 2;
$notes[1]->element_id = 34762720;
$amo->notes()->add($notes);

$note = $amo->notes()->create();
$note->note_type = 4;
$note->text = 'Amoapi v7';
$note->element_type = 1;
$note->element_id = 34762725;
$note->save();
```
Создание примечания из контакта, сделки или компании
```php
$note = $contact->createNote($type = 4);
$note = $lead->createNote($type = 4);
$note = $company->createNote($type = 4);
$note->text = 'Amoapi v7';
$note->element_type = 2;
$note->element_id = 34762728;
$note->save();
```

## Работа со списками
Получение всех списков (каталогов)
```php
$catalogs = $amo->catalogs;
```
Получение по ID
```php
$catalog = $amo->catalogs()->find($id); // array|integer
```
Получение списков с дополнительным условием
```php
$catalogs = $amo->catalogs()->where('key', $val)->call();
```
Связанные сущности по списку
```php
$elements = $catalog->elements;
```
Создание списков
```php
$catalogs = [
    $amo->catalogs()->create(),
    $amo->catalogs()->create()
];
$catalogs[0]->name = 'Amoapi v7 - 1';
$catalogs[1]->name = 'Amoapi v7 - 2';
$amo->catalogs()->add($catalogs);

$catalog = $amo->catalogs()->create();
$catalog->name = 'Amoapi v7';
$catalog->save();
```
Удаление списков
```php
$amo->catalogs()->delete($catalogs); // array|integer
$catalog->delete();
```

## Работа с элементами каталога (товарами)
Получение товаров
```php
$element = $amo->catalogElements()->find($id);
$elements = $amo->catalogElements()->where('catalog_id', 1234)->call();
$elements = $catalog->elements;
```
Добавление товаров
```php
$element = $amo->catalogElements()->create();
$element->catalog_id = 1234;
```
или
```php
$element = $catalog->createElement();
$element->name = 'Холодильник LG';
$element->cf('Артикул')->setValue('ML-4675');
$element->cf('Количество')->setValue(100);
$element->cf('Цена')->setValue(38500);
$element->save();
```
Обновление товаров
```php
$element->cf('Скидка')->setValue(5);
$element->save();
```
Связанные сущности по товару
```php
$catalog = $element->catalog;
$leads = $element->leads;
```
Удаление товаров
```php
$amo->elements()->delete($elements); // array|integer
$catalog->elements->delete(); // удаление всех товаров каталога
$element->delete();
```

## Работа с покупателями
Получение всех покупателей
```php
$customers = $amo->customers;
$customers = $amo->customers()->recursiveCall();
$customers = $amo->customers()->call(); // первые 500
```
Получение по ID
```php
$customer = $amo->customers()->find($id); // array|integer
```
Получение покупателей с дополнительным условием
```php
$customer = $amo->customers()->where('key', $val)->recursiveCall();
```
Связанные сущности покупателя
```php
$contact = $customer->contact;
$contacts = $customer->contacts;
$company = $customer->company;
$tasks = $customer->tasks;
$notes = $customer->notes;
$transactions = $customer->transactions;
```
Создание покупателей
```php
$customer = $amo->customers()->create();
$customer->name = 'Amoapi v7';
$customer->next_date = time();
$customer->next_price = 100;
$customer->responsible_user_id = $amo->account->currentUser->id;
$customer->cf('Число')->setValue(5);
$customer->cf('Текст')->setValue('Test');
$customer->cf('Мультисписок')->reset()->setValues(['Мужская одежда', 'Аксессуары']);
$customer->cf('Дата')->setValue(date('Y-m-d'));
$customer->cf('Переключатель')->disable();
$customer->save();
```
Удаление покупателей
```php
$amo->customers()->delete($customers); // array|integer
$customer->delete();
```

## Работа с покупками
Получение транзакций (покупок)
```php
$transactions = $amo->transactions;
$transactions = $customer->transactions;
```
Добавление транзакций
```php
$transaction = $amo->transactions()->create();
$transaction->customer_id = 1234;
```
или
```php
$transaction = $customer->createTransaction();
$transaction->price = 1500;
$transaction->save();
```
Обновление комментариев транзакций покупателя
```php
$transaction->comment = 'Тест';
$transaction->save();
```
Удаление транзакций покупателей
```php
$amo->transactions()->delete($transactions); // array|integer
$customer->transactions->delete(); // удаление всех покупок покупателя
$transaction->delete(); // удаление покупки
```

## Работа с веб-хуками
Получение вебхуков (webhooks)
```php
$webhooks = $amo->webhooks;
```
Добавление вебхуков
```php
$result = $amo->webhooks()->subscribe('http://site.ru/handler/', ['add_lead', 'update_contact', 'responsible_lead']);
```
Удаление вебхуков
```php
$result = $amo->webhooks()->unsubscribe('http://site.ru/handler/', ['update_contact', 'responsible_lead']);
```