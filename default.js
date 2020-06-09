const URL = 'getdata.php';

const pageN = document.getElementById('pageN'),
    countRec = document.getElementById('countRec'),
    table = document.getElementById('datagrid'),
    orderPage = document.getElementById('orderPage');

const DBQuery = class {
    getData = async (url) => {
        const res = await fetch(url);
        if (res.ok) {
            return res.json();
        } else {
            throw new Error(`По адресу ${url} сервер вернул ${res.status}`);
        }
    }

    getPage = (page, order, count) => {
        //page - запрашиваемая страница, order - сортировка по столбцу, count - количество записей на странице
        return this.getData(URL + '?page=' + page + '&order=' + order + '&count=' + count);
    }
}

const getPage = (page, order, count) => {
    new DBQuery().getPage(page - 1, order, count).then(recreateTable);
}

const recreateTable = (response) => {
    table.innerText = "";
    let pageCount = response.pageCount;
    let page = response.page;
    pageN.innerText = '';
    for (let i = 1; i < pageCount + 1; i++) {
        let pageOption = document.createElement('option');
        pageOption.innerText = i;
        pageOption.value = i;
        if (page + 1 == i) pageOption.selected = true;
        pageN.append(pageOption);
    }
    const rowHeader = response.fieldList;
    const tr = document.createElement('tr');
    orderPage.innerText = '';
    console.log(response);
    rowHeader.forEach(field => {
        let th = document.createElement('th');
        th.dataset.fieldName = field[0];
        th.innerText = field[1];
        tr.append(th);
        let pageOrderOption = document.createElement('option');
        pageOrderOption.innerText = field[1];
        pageOrderOption.value = field[0];
        pageOrderOption.selected = (field[0] == response.order);
        orderPage.append(pageOrderOption);
    });
    table.append(tr);
    const rowData = response.fieldData;
    rowData.forEach(dataRec => {
        console.log(dataRec);
        let tr = document.createElement('tr');
        for (rec in dataRec) {
            let td = document.createElement('td');
            td.innerText = dataRec[rec];
            td.dataset.fieldName = rec;
            tr.append(td);
        }
        table.append(tr);
    });

}

const reload = () => {
    getPage(pageN.options[pageN.selectedIndex].value,
        orderPage.options[orderPage.selectedIndex].value,
        countRec.options[countRec.selectedIndex].value);
};

const init = () => {
    getPage(1, 'ArticleId', 25);
}