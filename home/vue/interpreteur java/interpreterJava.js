document.getElementById('runButton').addEventListener('click', function () {
    const code = document.getElementById('code').value;
    const outputDiv = document.getElementById('output');

    $.ajax({
        url: 'https://eval.repl.it/eval/<https://replit.com/@carvalhoclement/testSAE#Main.java>',
        method: 'POST',
        dataType: 'json',
        contentType: 'application/json',
        data: JSON.stringify({ code: code }),
        success: function (result) {
            outputDiv.innerText = "Result of execution:\n" + result.output;
        },
        error: function (error) {
            outputDiv.innerText = "Error during execution:\n" + error.responseText;
        }
    });
});
