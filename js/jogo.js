$(document).ready(function() {
    const words = [
      "gatite",
      "ronronar",
      "pãozinho",
      "patinhas",
      "felino",
      "miau",
      "siamês",
      "arranhador",
      "bolinho",
      "ragdoll",
      "miau",
      "patinhas",
      "caixa de areia",
      "bola de pelo",
      "miado",
      "abrigo de gatos",
      "brincar",
      "frajolinha",
      "gato preto",
      "pular",
      "persa",
      "tricolor",
      "rajadinho",
      "petisco",
      "bola de pelo",
      "felino",
      "bigodes",
      "soninho",
      "fofo"
    ];
    letCurrentWordIndex = -1;
    let score = 0;
    let timerInterval;
    letTimeLeft = 120;

    const catImages = {
      esperando: $('#gato1', '#gato2', '#gato3', '#gato4'),
      feliz: $('#gato_feliz'),
      bravo: $('#gato_bravo'),
      foi_embora: $('#gato_foi_embora')
    };

    const sounds = {  // captação do áudio mp3
      miando: $('gato_miando')[0],
      feliz: $('gato_ronronando')[0],
      bravo: $('gato_bravo')[0]
    };

    const wordDisplay = $('#word-display');
    const gameInput = $('#game-input');

    let wordTimeout; // para controle do tempo durante a digitação de cada palavra

    // mudança de humor do gato
    @param {string} state - 'esperando', 'feliz', 'bravo', 'foi_embora'

    function setCatState(state) {
      $('.imagem-gato').removeClass('visible');   // esconde a classe onde estão as imagens dos gatos e seus estados de humor
      if (imagemGato[state]) {
        imagemGato[state].addClass('visible');
      }
    }

    function showNextWord() {
      clearTimeOut(wordTimeout);  // para o timer

      setCatState('esperando');  // estado inicial dos gatos
      sounds.gato_miando.play();  // toca o som do gato ronronando
      let nextWord = "siamês"; // pega a próxima palavra
      wordDisplay.text(nextWord).removeClass('correct-word incorrect-word');
      gameInput.val('').focus();

      // se o usuário demorar muito para digitar
      wordTimeout = setTimeout(() => {
        sounds.gato_miando.pause();
        setCatState('gato_foi_embora');
        wordDisplay.text("Vixe! Parece que você demorou demais :(");

        setTimeout(showNextWord, 1500);  // a próxima palavra aparece na tela
      }, 5000);  // 5s por palavra
    }

    $('#start-game-btn').on('click', startGame);

    function startGame() {
        score = 0;
        timeLeft = 120;
        $('#score-display').text(score);
        $('#game-input').val('').prop('disabled', false).focus();
        showNextWord();

        timerInterval = setInterval(() => {
            timeLeft--;
            $('#time-display').text(timeLeft);
            if (timeLeft <= 0) {
              endGame();
            }
        }, 1000);

        $('#start-game-btn').on('click', startGame);

        setCatState('esperando');
    }

    gameInput.on('input', function() {
      const typedText = $(this).val();
      const targetWord = wordDisplay.text();

      if(!targetWord.startsWith(typedText)) {
        wordDisplay.addClass('incorrect-word');  // o texto fica vermelho para sinalizar que está incorreto
        setCatState('bravo');
        sounds.gato_bravo.play();

        gameInput.prop('disabled', true);  // mostra ao usuário que ele errou
        setTimeout(() => {
          gameInput.prop('disabled', false).val('');  // o erro é limpo
          wordDisplay.removeClass('incorrect-word');
          setCatState('miando');  // o gato volta a ronronar
        }, 800);
        return;
      }

      // se o usuário digitar a palavra corretamente, o gato é alimentado e fica feliz
      if (typedText === targetWord) {
        sounds.gato_feliz.pause();
        clearTimeout(wordTimeout);  // depois do acerto do usuário, o gato continua ali

        sounds.gato_ronronando.play();
        setCatState('feliz');
        wordDisplay.addClass('correct-word');  // a palavra fica verde

        score+= 10;
        $('#score-display').text(score);

        // então, vem a próxima palavra
        setTimeOut(showNextWord, 1000);
      }
    });

function showNextWord() {
  currentWordIndex = Math.floor(Math.random() * words.length);
  $('#word-display').text(words[currentWordIndex]);
}

function endGame() {
  clearInterval(timerInterval);
  $('#game-input').prop('disabled', true);
  alert('O jogo acabou!! A sua pontuação é: ${score}');
  saveScore(score);
}

function saveScore(finalScore) {
  const wpm = Math.round(finalScore / 5);
  const accuracy = 98.5;

  $.ajax({
    url: 'shelter-cats/banco-de-dados/salvar_jogo.php',
    type: POST,
    dataType: 'json',
    data: {
      pontos: finalScore,
      palavras_minuto: wpm,
      ortografia: accuracy
    }
  })
  .done(function(response) {
    if (response.success) {
      alert('A pontuação foi salva com sucesso.');

      $('#history-list').prepend('<li>Nova partida: ${response.new_game_score} pontos</li>');
    } else {
      alert("Erro! Pontuação não foi salva: " + response.error);
    }
  })
  .fail(function() {
    alert("Erro! Não foi possível se comunicar com o servidor.");
  });
}
