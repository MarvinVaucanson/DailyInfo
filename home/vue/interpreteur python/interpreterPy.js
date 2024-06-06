document.addEventListener('DOMContentLoaded', function () {
    const codeTextArea = document.getElementById('code');
    const testButton = document.getElementById('testButton');
    const submitButton = document.getElementById('submitButton');
    const outputDiv = document.getElementById('output');

    let testedSuccessfully = false;

    testButton.addEventListener('click', function () {
        if(difficulte == 1){
            outputDiv.innerText = "";

            const code = codeTextArea.value;

            try {
                Sk.configure({
                    output: function (text) {
                        outputDiv.innerText += text;
                        // Mettez à jour la valeur du champ caché avec le contenu de la balise output
                        hiddenOutput.value = outputDiv.innerText;
                    }
                });

                Sk.misceval.asyncToPromise(function () {
                    return Sk.importMainWithBody("<stdin>", false, code, true);
                }).then(function (module) {
                    console.log('Python code executed successfully.');
                    // Le code a été testé avec succès
                    testedSuccessfully = true;
                }, function (error) {
                    outputDiv.innerText += "\nError during execution:\n" + error.toString();
                    // Le code n'a pas été testé avec succès
                    testedSuccessfully = false;
                });
            } catch (error) {
                outputDiv.innerText = "Erreur: " + error.toString();
                testedSuccessfully = false;
            }
        }else{
            outputDiv.innerText = "";
            assertResultDiv.innerText = "";

            const code = codeTextArea.value;

            try {
                Sk.configure({
                    output: function (text) {
                        outputDiv.innerText += text;
                        hiddenOutput.value = outputDiv.innerText;
                    }
                });

                Sk.misceval.asyncToPromise(function () {
                    return Sk.importMainWithBody("<stdin>", false, code, true);
                }).then(function (module) {
                    console.log('Python code executed successfully.');
                    testedSuccessfully = true;

                    if (module.$denergize(valeurTest)) {
                        hiddenAssertResult.value = 'true';
                        assertResultDiv.innerText = 'true';
                    } else {
                        hiddenAssertResult.value = 'false';
                        assertResultDiv.innerText = 'false';
                    }
                    
                }, function (error) {
                    outputDiv.innerText += "\nError during execution:\n" + error.toString();
                    testedSuccessfully = false;
                    assertResultDiv.innerText = "false";
                    hiddenAssertResult.value = 'false';
                });
            } catch (error) {
                outputDiv.innerText = "Erreur: " + error.toString();
                testedSuccessfully = false;
                assertResultDiv.innerText = "false";
                hiddenAssertResult.value = 'false';
            }
        }
        
    });
});