const _elements = {
    loading: document.querySelector(".loading"),
    switch: document.querySelector(".switch__track"),
    stateSelectToggle: document.querySelector(".state-select-toggle"),
    selectOptions: document.querySelectorAll(".state-select-list__item"),
    selectList: document.querySelector(".state-select-list"),
    selectToggleIcon: document.querySelector(".state-select-toggle__icon"),
    selectSearchBox: document.querySelector(".state-select-list__search"),
    atividade: document.querySelector(".info__total--Metas"),
    alimentacao: document.querySelector(".info__total--alimentar"),
    humorGeral: document.querySelector(".info__total--humor"),
    atividadesPendentes: document.querySelector(".info__total--atividades"),
}

const _data = {
    id: "Todos=true",
    atividade: undefined,
    alimentacao: undefined,
    humorGeral: undefined,
    atividadesPendentes: undefined,
}

const _charts = {};
const _elements2 = {
    loading: document.querySelector(".loading"),
    switch: document.querySelector(".switch__track"),
    stateSelectToggle: document.querySelector(".state-select-2 .state-select-toggle"),
    selectOptions: document.querySelectorAll(".state-select-2 .state-select-list__item"),
    selectList: document.querySelector(".state-select-2 .state-select-list"),
    selectToggleIcon: document.querySelector(".state-select-2 .state-select-toggle__icon"),
    selectSearchBox: document.querySelector(".state-select-2 .state-select-list__search"),
};

_elements.switch.addEventListener("click", () => {
    const isDark = _elements.switch.classList.toggle("switch__track--dark");
    if(isDark == true){
        document.documentElement.setAttribute("data-theme","dark");
    } else{
        document.documentElement.setAttribute("data-theme","modern");
    }
    
});


document.querySelectorAll(".state-select, .state-select-2").forEach((selectBox) => {
    const toggle = selectBox.querySelector(".state-select-toggle");
    const toggleIcon = selectBox.querySelector(".state-select-toggle__icon");
    const selectList = selectBox.querySelector(".state-select-list");
    const searchBox = selectBox.querySelector(".state-select-list__search");
    const listItems = selectBox.querySelectorAll(".state-select-list__item");

    toggle.addEventListener("click", () => {
        toggleIcon.classList.toggle("state-select-toggle__icon--rotate");
        selectList.classList.toggle("state-select-list--show");
    });

    searchBox.addEventListener("input", (event) => {
        const searchTerm = event.target.value.toLowerCase();
        listItems.forEach((item) => {
            const text = item.textContent.toLowerCase();
            if (text.includes(searchTerm)) {
                item.classList.remove("state-select-list__item--hide");
            } else {
                item.classList.add("state-select-list__item--hide");
            }
        });
    });

    listItems.forEach((item) => {
        item.addEventListener("click", () => {
            toggle.querySelector(".state-select-toggle__state-selected").textContent = item.textContent;
            toggleIcon.classList.remove("state-select-toggle__icon--rotate");
            selectList.classList.remove("state-select-list--show");
        });
    });
});
const createBasicChart = (element, config) => {
    const options = {
        chart: {
            background:"transparent"
        },
        xaxis:{
            type:"datetime"
        },
        series: []
    }
    const chart = new ApexCharts(document.querySelector(element),options);
    chart.render();
    return chart
}
const createChartAtividades = (element, config) => {
    const optionsAtividades = {
        series: [
            {
                name: "Corrida",
                data: [5, 10, 8, 15, 12, 7, 14] // Exemplo de dados para corrida
            },
            {
                name: "Musculação",
                data: [4, 9, 7, 13, 10, 6, 12] // Exemplo de dados para musculação
            },
            {
                name: "Yoga",
                data: [3, 6, 5, 9, 8, 4, 10] // Exemplo de dados para yoga
            }
        ],
        chart: {
            type: "bar",
            height: 250,
            stacked: false // Desativa o empilhamento para agrupamento
        },
        xaxis: {
            categories: ["Seg", "Ter", "Qua", "Qui", "Sex", "Sáb", "Dom"] // Dias da semana
        },
        title: {
            text: "Atividades por Dia Por tipo de exercicio",
            align: "left"
        },
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '60%', // Ajusta a largura das colunas
            }
        }
    };
    
    const chartAtividades = new ApexCharts(document.querySelector(element), optionsAtividades);
    chartAtividades.render();
    return chartAtividades;
};
const Executado_por_Plano = (element, config) => {
    const optionsAtividades = {
        series: [
            {
                name: "Plano",
                data: [0, 2, 2, 2, 2, 2, 0] // Quantidade de atividades por dia
            },
            {
                name: "Executado",
                data: [1, 0, 0, 1, 0, 1, 0]
            }
        ],
        chart: {
            type: "line",
            height: 250
        },
        xaxis: {
            categories: ["Dom","Seg", "Ter", "Qua", "Qui", "Sex", "Sáb", ] // Dias da semana
        },
        title: {
            text: "Atividades Executado | Plano",
            align: "left"
        }
    };
    const chartAtividades = new ApexCharts(document.querySelector(element), optionsAtividades);
    chartAtividades.render();
    return chart;
}

const createChartHumorPorAtividade = (element, config) => {
    const optionsAtividades = {
        series: [
            {
                name: "Feliz",
                data: [5, 10, 8, 15, 12, 7, 14] // Exemplo de dados para corrida
            },
            {
                name: "Triste",
                data: [4, 9, 7, 13, 10, 6, 12] // Exemplo de dados para musculação
            },
            {
                name: "Raiva",
                data: [3, 6, 5, 9, 8, 4, 10] // Exemplo de dados para yoga
            }
        ],
        chart: {
            type: "bar",
            height: 250,
            stacked: true // Desativa o empilhamento para agrupamento
        },
        xaxis: {
            categories: ["Corrida", "Musculação","Yoga"] // Dias da semana
        },
        title: {
            text: "Humor por tipo de atividade",
            align: "left"
        },
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '60%', // Ajusta a largura das colunas
            }
        }
    };
    
    const chartAtividades = new ApexCharts(document.querySelector(element), optionsAtividades);
    chartAtividades.render();
    return chartAtividades;
};

const createCharts = () => {
    _charts.AtividadesDate = createChartAtividades(".data-box--Atividades-Date .data-box__body");
    _charts.HumorAgrupado = createChartHumorPorAtividade(".data-box--HumorAgrupado .data-box__body");
    _charts.Alimentacao = Executado_por_Plano(".data-box--Alimentacao .data-box__body");
 
}

const loadData = (id) => {
    _data.atividade=  20;
    _data.alimentacao =  5;
    _data.atividadesPendentes=  9;
    _data.humorGeral =  "Feliz";


    updateCards();
}

const updateCards = () => {
    _elements.atividade.innerText = _data.atividade;
    _elements.alimentacao.innerText = _data.alimentacao;
    _elements.atividadesPendentes.innerText = _data.atividadesPendentes;
    _elements.humorGeral.innerText = _data.humorGeral;
}


loadData(_data.id);
createCharts();