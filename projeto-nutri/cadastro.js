function calcularIMC() {
    const nome = document.getElementById('nome').value;
    const idade = parseInt(document.getElementById('idade').value);
    const peso = parseFloat(document.getElementById('peso').value);
    const altura = parseFloat(document.getElementById('altura').value);
  
    if (!nome || !idade || !peso || !altura) {
      alert("Por favor, preencha todos os campos corretamente.");
      return;
    }
  
    // Enviar para o PHP via fetch
    fetch("salvar.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ nome, idade, peso, altura })
    })
    .then(response => response.json())
    .then(data => {
      if (data.mensagem) {
        document.getElementById('resultado').innerText = data.mensagem;
      } else {
        document.getElementById('resultado').innerText = "Erro ao calcular ou salvar.";
      }
    })
    .catch(error => {
      console.error("Erro:", error);
      document.getElementById('resultado').innerText = "Erro de conexão com o servidor.";
    });
  }
  