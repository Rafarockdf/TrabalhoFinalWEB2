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
};

const _charts = {};

const _data = {
    id: "Todos=true",
    atividade: undefined,
    alimentacao: undefined,
    humorGeral: undefined,
    atividadesPendentes: undefined,
};
function configurarEventoIconePerfil() {
    const profileIcon = document.querySelector('.profile-icon');
    console.log('Ícone de perfil encontrado:', profileIcon);

    if (profileIcon) {
        profileIcon.addEventListener('click', (event) => {
            console.log('Ícone de perfil clicado', event.target);

            if (_elements.loading) {
                _elements.loading.classList.remove('loading--hide');
            }

            // Verifica a sessão do usuário
            fetch('../../Verificar/verificar_sessao.php')
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`Erro HTTP! Status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (_elements.loading) {
                        _elements.loading.classList.add('loading--hide');
                    }

                    if (data.logado) {
                        console.log('Usuário logado. Redirecionando para perfil...');
                        window.location.href = '../../Perfil/html/perfil.html';
                    } else {
                        console.log('Usuário não logado. Redirecionando para tela intermediária...');
                        window.location.href = '../../Tela Intermediaria/html/telaLoginCadastro.html';
                    }
                })
                .catch(error => {
                    console.error('Erro ao verificar sessão:', error);
                    if (_elements.loading) {
                        _elements.loading.classList.add('loading--hide');
                    }
                    alert('Não foi possível verificar o status da sessão. Tente novamente.');
                });
        });
    } else {
        console.warn("Elemento '.profile-icon' não encontrado.");
    }
}

// Aguarda o carregamento do DOM
document.addEventListener("DOMContentLoaded", () => {
    console.log("DOM totalmente carregado. Inicializando...");
    configurarEventoIconePerfil();
});
document.querySelector(".menu-toggle").addEventListener("click", () => {
    document.querySelector(".sidebar").classList.toggle("collapsed");
    document.querySelector(".menu-toggle").classList.toggle("collapsed");
    document.querySelector(".container").classList.toggle("collapsed");
});

_elements.switch.addEventListener("click", () => {
    const isDark = _elements.switch.classList.toggle("switch__track--dark");
    document.documentElement.setAttribute("data-theme", isDark ? "dark" : "modern");
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
            item.classList.toggle("state-select-list__item--hide", !item.textContent.toLowerCase().includes(searchTerm));
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

const createChartAtividades = (element) => {
    const container = document.querySelector(element);
    if (!container) {
        console.warn("Elemento para gráfico de atividades não encontrado:", element);
        return null;
    }

    const options = {
        series: [],
        chart: {
            type: "bar",
            height: 250,
            stacked: false,
            background: "transparent"
        },
        xaxis: {
            categories: []
        },
        title: {
            text: "Atividades por Mês por Tipo",
            align: "left"
        },
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '60%',
            }
        }
    };
    const chart = new ApexCharts(container, options);
    chart.render();
    return chart;
};

const Executado_por_Plano = (element) => {
    const container = document.querySelector(element);
    if (!container) {
        console.warn("Elemento para gráfico de alimentação não encontrado:", element);
        return null;
    }

    const options = {
        series: [
            { name: "Planejado", data: [] },
            { name: "Executado", data: [] }
        ],
        chart: {
            type: "line",
            height: 250,
            background: "transparent"
        },
        xaxis: {
            categories: []
        },
        title: {
            text: "Atividades Planejadas vs Executadas por Dia",
            align: "left"
        }
    };

    const chart = new ApexCharts(container, options);
    chart.render();
    return chart;
};


const createChartHumorPorAtividade = (element) => {
    const container = document.querySelector(element);
    if (!container) {
        console.warn("Elemento para gráfico de humor não encontrado:", element);
        return null;
    }

    const options = {
        series: [],
        chart: {
            type: "bar",
            height: 250,
            stacked: true,
            background: "transparent"
        },
        xaxis: {
            categories: []
        },
        title: {
            text: "Humor por tipo de atividade",
            align: "left"
        },
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '60%',
            }
        }
    };
    const chart = new ApexCharts(container, options);
    chart.render();
    return chart;
};
const updateChartPlanoVsExecutado = (dados) => {
    if (!_charts.Alimentacao) return;

    const categorias = dados.map(d => d.data);
    const planejado = dados.map(d => d.planejado);
    const executado = dados.map(d => d.executado);

    _charts.Alimentacao.updateOptions({
        xaxis: { categories: categorias },
        series: [
            { name: "Planejado", data: planejado },
            { name: "Executado", data: executado }
        ]
    });
};

const updateChartAtividades = (dados) => {
    if (!_charts.AtividadesDate) return;
    const meses = [...new Set(dados.map(d => d.Mes))];
    const tipos = [...new Set(dados.map(d => d.Atividade))];

    const series = tipos.map(tipo => ({
        name: tipo,
        data: meses.map(mes => {
            const match = dados.find(d => d.Atividade === tipo && d.Mes === mes);
            return match ? parseInt(match.Contagem) : 0;
        })
    }));

    console.log("Atualizando gráfico de atividades:", { series, meses });

    _charts.AtividadesDate.updateOptions({
        series,
        xaxis: {
            categories: meses.map(m => `Mês ${m}`)
        }
    });
};

const updateChartHumor = (dados) => {
    if (!_charts.HumorAgrupado) return;
    const tipos = [...new Set(dados.map(d => d.Atividade))];
    const sentimentos = [...new Set(dados.map(d => d.Sentimento))];

    const series = sentimentos.map(sentimento => ({
        name: sentimento,
        data: tipos.map(tipo => {
            const match = dados.find(d => d.Atividade === tipo && d.Sentimento === sentimento);
            return match ? parseInt(match.Contagem) : 0;
        })
    }));

    console.log("Atualizando gráfico de humor:", { series, tipos });

    _charts.HumorAgrupado.updateOptions({
        series,
        xaxis: {
            categories: tipos
        }
    });
};

const loadData = async (id) => {
    const response = await fetch("../php/dashboard.php");
        const data = await response.json();

        console.log("Dados recebidos:", data);
    try {
        const response = await fetch("../php/dashboard.php");
        const data = await response.json();

        console.log("Dados recebidos:", data);

        _data.atividade = data.card1['total_pendentes'];
        _data.alimentacao = data.card2['media_calorias'];


        _data.atividadesPendentes = data.card4['total_pendentes'];
        _data.humorGeral = data.card3['media_calorias'];
        updateCards();

        if (Array.isArray(data.grafico1)) updateChartAtividades(data.grafico1);
        if (Array.isArray(data.grafico2)) updateChartHumor(data.grafico2);
        if (Array.isArray(data.graficoPlanoVsExecutado)) {
            updateChartPlanoVsExecutado(data.graficoPlanoVsExecutado);
        }
        

    } catch (error) {
        console.error("Erro ao carregar dados:", error);
        console.log("Dados recebidos:", data);
    }
};

const updateCards = () => {
    _elements.atividade.innerText = _data.atividade ?? "–";
    _elements.alimentacao.innerText = _data.alimentacao ?? "-"; 
    _elements.atividadesPendentes.innerText = _data.atividadesPendentes ?? "–";
    _elements.humorGeral.innerText = _data.humorGeral ?? "–";
};

const createCharts = () => {
    _charts.AtividadesDate = createChartAtividades(".data-box--Atividades-Date .data-box__body");
    _charts.HumorAgrupado = createChartHumorPorAtividade(".data-box--HumorAgrupado .data-box__body");
    _charts.Alimentacao = Executado_por_Plano(".data-box--Alimentacao .data-box__body");
};

// Ordem correta: criar os gráficos primeiro, depois carregar os dados
createCharts();
loadData(_data.id);
