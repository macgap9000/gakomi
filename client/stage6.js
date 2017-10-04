// Plik JavaScript konieczny do obsługi kopiowania tokena:
function copyTokenFromBox()
{
    // Przygotowanie uchwytu do tokenboxa:
    var tokenbox = document.getElementById("tokenbox");
    // Przygotowanie uchwytu do tokenbuttona:
    var tokenbutton = document.getElementById("tokenbutton");
    
    // Ustaw focus na tokenboxie:
    tokenbox.focus();
    
    // Zaznacz zawartość tokenboxa:
    tokenbox.select();
    
    // Podejmij próbę skopiowania wartości do schowka:
    try 
    {
        // Przypisanie do zmiennej wyniku operacji kopiowania do schowka:
        var isCopiedSuccessfully = document.execCommand('copy');
        
        // Zmiany w tokenbuttonie w zależności od powodzenia operacji:
        if (isCopiedSuccessfully == true)
        {
            // Wyrzucenie komunikatu o sukcesie kopiowania tokena do konsoli:
            console.log('Token skopiowany pomyślnie!');

            // Ustawienie stylu buttona na sukces:
            setTokenbuttonToSuccess();
            
            // Powrót do starego wyglądu buttona po 5 sekundach:
            setTimeout(setTokenbuttonToDefault, 3000);
        }
        else
        {
            // Wyrzucenie komunikatu o problemie ze skopiowaniem tokena do konsoli:
            console.log('Wystąpił problem przy kopiowaniu tokena.');

            // Ustawienie stylu buttona na porażkę:
            setTokenbuttonToFailure();
            
            // Powrót do starego wyglądu buttona po 5 sekundach:
            setTimeout(setTokenbuttonToDefault, 3000);
        }
      
    } 
    catch (err) 
    {
        // Wyrzucenie komunikatu o problemie ze skopiowaniem tokena do konsoli:
        console.log('Wystąpił problem przy kopiowaniu tokena.');
        
        // Ustawienie stylu buttona na porażkę:
        setTokenbuttonToFailure();
          
        // Powrót do starego wyglądu buttona po 5 sekundach:
        setTimeout(setTokenbuttonToDefault, 3000);
    }

}

// Ustawienie buttona kopiowania tokena na styl normalny:
function setTokenbuttonToDefault()
{
    // Przygotowanie uchwytu do tokenboxa:
    var tokenbox = document.getElementById("tokenbox");
    // Przygotowanie uchwytu do tokenbuttona:
    var tokenbutton = document.getElementById("tokenbutton");
    
    // Odznaczenie tokenboxa:
    tokenbox.blur();
    // Ustawienie domyślnego tekstu tokenbuttonowi:
    tokenbutton.innerText = "Kopiuj token";
    // Ustawienie domyślnego koloru tokenbuttona:
    tokenbutton.className = "btn btn-default";
}

// Ustawienie buttona na sukces (skopiowano tokena pomyślnie):
function setTokenbuttonToSuccess()
{      	  
    // Przygotowanie uchwytu do tokenbuttona:
    var tokenbutton = document.getElementById("tokenbutton");
    
    // Ustawienie tekstu w tokenbuttonie:
    tokenbutton.innerText = "Skopiowano pomyślnie!";
    // Zmiana koloru tokenbuttona:
    tokenbutton.className = "btn btn-success";
}

// Ustawienie buttona na porażkę (problem ze skopiowaniem tokena):
function setTokenbuttonToFailure()
{
    // Przygotowanie uchwytu do tokenbuttona:
    var tokenbutton = document.getElementById("tokenbutton");
    
    // Ustawienie tekstu w tokenbuttonie:
    tokenbutton.innerText = "Błąd kopiowania!";
    // Zmiana koloru tokenbuttona:
    tokenbutton.className = "btn btn-danger";
}