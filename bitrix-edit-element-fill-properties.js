    var n = `ООО"Уральский технический центр"
ООО"Челябинск-Хино"
ООО"Шининвест"
ООО"Элефант-Проспект"
ООО"ЭЛИС ЛМ"
ООО"ЮРМА-СЕРВИС"
ПАО "Сбербанк"
ПАО "Совкомбанк"
ПАО "ЧЕЛИНДБАНК"`;

    a = n.split(`\n`);

    $.each(a, function (index, value) {
        index++;
        $("#PROPERTY_VALUES_NAME_n" + index).val(value);
    });
