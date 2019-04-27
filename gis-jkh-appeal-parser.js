allFields = {};
$("tr").get().forEach(function (element) {
    let key = true;
    let name;
    let doubleIndex = 0;
    Array.from(element.children).forEach(function (child) {
        if (key) {
            name = $(child)[0].textContent.trim();
        }
        if (!key) {
            const value = $(child)[0].textContent.trim();
            if (typeof allFields[name] !== typeof undefined) {
                name = `${name}-${doubleIndex}`;
                doubleIndex++;
            }
            allFields[name] = value;
        }
        key = !key;
    })
});
JSON.stringify(allFields);
