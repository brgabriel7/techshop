<!--
  Módulo voltar topo da plataforma TechShop.
  Projecto: TechShop — Loja Virtual de Acessórios Informáticos
  Autor: Bernabé Rofino Gabriel
  Instituição: Universidade Licungo
  Curso: Mestrado em Tecnologias Educativas e Inovação
  Módulo: Aplicações Web
  Docente: Prof. Félix Singo
  Ano: 2026
  Direitos: Todos os direitos reservados.
-->
<!-- Botão voltar ao topo -->
<button id="voltarTopo" type="button" title="Voltar ao topo" aria-label="Voltar ao topo">
    <svg viewBox="0 0 60 60">
        <circle class="circulo-fundo" cx="30" cy="30" r="25"/>
        <circle id="progresso" class="circulo-progresso" cx="30" cy="30" r="25"/>
    </svg>
    <span class="seta-topo">↑</span>
    <span id="percentagem">0%</span>
</button>

<style>
#voltarTopo {
    position: fixed;
    bottom: 25px;
    right: 25px;
    width: 65px;
    height: 65px;
    border: none;
    border-radius: 50%;
    background: white;
    color: #0877c5;
    box-shadow: 0 4px 18px rgba(0,0,0,.20);
    cursor: pointer;
    z-index: 99999;
    display: none;
    align-items: center;
    justify-content: center;
    flex-direction: column;
}

#voltarTopo svg {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    transform: rotate(-90deg);
}

.circulo-fundo, .circulo-progresso {
    fill: none;
    stroke-width: 3;
}

.circulo-fundo { stroke: #dcebf5; }

.circulo-progresso {
    stroke: #0877c5;
    stroke-linecap: round;
    stroke-dasharray: 157.08;
    stroke-dashoffset: 157.08;
}

.seta-topo {
    font-size: 23px;
    font-weight: bold;
    line-height: 1;
}

#percentagem {
    font-size: 11px;
    font-weight: bold;
}
</style>

<script>
(function () {
    const botao = document.getElementById('voltarTopo');
    const circulo = document.getElementById('progresso');
    const texto = document.getElementById('percentagem');

    if (!botao || !circulo || !texto) return;

    const comprimento = 2 * Math.PI * 25;

    function actualizarProgresso() {
        const altura = document.documentElement.scrollHeight -
                       document.documentElement.clientHeight;

        const posicao = window.scrollY ||
                        document.documentElement.scrollTop;

        const percentagem = altura > 0
            ? Math.min(100, Math.round((posicao / altura) * 100))
            : 0;

        texto.textContent = percentagem + '%';

        circulo.style.strokeDashoffset =
            comprimento * (1 - percentagem / 100);

        botao.style.display = posicao > 100 ? 'flex' : 'none';
    }

    botao.addEventListener('click', function () {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });

    window.addEventListener('scroll', actualizarProgresso);
    window.addEventListener('resize', actualizarProgresso);

    actualizarProgresso();
})();
</script>
