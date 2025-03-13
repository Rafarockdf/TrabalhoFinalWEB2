document.addEventListener("DOMContentLoaded", function () {
    const dietasContainer = document.getElementById("dietas-container");
    const treinosContainer = document.getElementById("treinos-container");
    const showDietasBtn = document.getElementById("show-dietas");
    const showTreinosBtn = document.getElementById("show-treinos");

    const dietas = [
        {
            title: "🥗 Dieta Mediterrânea",
            description: "Baseada em alimentos frescos, azeite de oliva, peixes e grãos integrais.",
            details: ["✅ Rica em gorduras saudáveis", "✅ Boa para o coração", "✅ Incentiva consumo de frutas e vegetais"]
        },
        {
            title: "🥩 Dieta Low-Carb",
            description: "Reduz carboidratos e foca em proteínas e gorduras boas.",
            details: ["✅ Auxilia na perda de peso", "✅ Controla a insulina", "✅ Reduz fome e compulsão alimentar"]
        },
        {
            title: "🍚 Dieta Rica em Proteínas",
            description: "Foca em alimentos ricos em proteínas para ganho muscular.",
            details: ["✅ Ajuda na recuperação muscular", "✅ Aumenta a saciedade", "✅ Importante para atletas"]
        }
    ];

    const treinos = [
        {
            title: "🏋️ Treino de Força",
            description: "Focado em levantamento de peso e ganho de massa muscular.",
            details: ["✅ Desenvolvimento de músculos", "✅ Aumento da resistência", "✅ Ideal para hipertrofia"]
        },
        {
            title: "🏃 Treino de Resistência",
            description: "Exercícios aeróbicos para melhorar o condicionamento físico.",
            details: ["✅ Aumenta a resistência cardiovascular", "✅ Melhora a saúde do coração", "✅ Auxilia na queima de calorias"]
        },
        {
            title: "🧘 Treino de Flexibilidade",
            description: "Inclui yoga e alongamentos para melhorar a mobilidade.",
            details: ["✅ Melhora a mobilidade articular", "✅ Reduz o risco de lesões", "✅ Ajuda no relaxamento"]
        }
    ];

    function renderCards(lista, container) {
        container.innerHTML = "";
        lista.forEach(item => {
            const cardItem = document.createElement("div");
            cardItem.classList.add("card-item");
            cardItem.innerHTML = `
                <div class="card-title">${item.title}</div>
                <div class="card-content">
                    <p>${item.description}</p>
                    <ul>${item.details.map(detail => `<li>${detail}</li>`).join("")}</ul>
                </div>
            `;

            cardItem.addEventListener("click", function () {
                this.classList.toggle("active");
            });

            container.appendChild(cardItem);
        });
    }

    showDietasBtn.addEventListener("click", function () {
        if (dietasContainer.innerHTML === "") {
            renderCards(dietas, dietasContainer);
        } else {
            dietasContainer.innerHTML = "";
        }
        showDietasBtn.classList.toggle("active");
    });

    showTreinosBtn.addEventListener("click", function () {
        if (treinosContainer.innerHTML === "") {
            renderCards(treinos, treinosContainer);
        } else {
            treinosContainer.innerHTML = "";
        }
        showTreinosBtn.classList.toggle("active");
    });

    // Renderiza os dois por padrão
    renderCards(dietas, dietasContainer);
    renderCards(treinos, treinosContainer);
});
