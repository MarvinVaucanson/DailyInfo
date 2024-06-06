document.addEventListener('DOMContentLoaded', function () {
    const codeTextArea = document.getElementById('code');
    const testButton = document.getElementById('testButton');
    const submitButton = document.getElementById('submitButton');
    const outputDiv = document.getElementById('output');
    const assertResultDiv = document.getElementById('assertResult');
    const hiddenOutput = document.getElementById('hiddenOutput');
    const hiddenAssertResult = document.getElementById('hiddenAssertResult');

    let testedSuccessfully = false;

    testButton.addEventListener('click', function () {
        outputDiv.innerText = "";
        assertResultDiv.innerText = "";

        const code = codeTextArea.value;

        try {
            outputDiv.innerText = "";

            const result = eval(code);

            outputDiv.innerText += result;

            testedSuccessfully = true;

            if (difficulte !== 1) {     
                try {
                    const assertResult = eval(valeurTest);
                    if (assertResult) {
                        hiddenAssertResult.value = 'true';
                        assertResultDiv.innerText = 'true';
                    } else {
                        hiddenAssertResult.value = 'false';
                        assertResultDiv.innerText = 'false';
                    }
                } catch (error) {
                    console.error("Erreur lors de l'exécution de l'assert : " + error);
                    hiddenAssertResult.value = 'false';
                    assertResultDiv.innerText = 'false';
                }
            }
        } catch (error) {
            outputDiv.innerText = "Erreur lors de l'exécution :\n" + error.message;

            testedSuccessfully = false;
            assertResultDiv.innerText = "false";
            hiddenAssertResult.value = 'false';
        }
    });
});
