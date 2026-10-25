#include <stdio.h>
#include <string.h>
#include <stdbool.h>


int main(){

    
    char password[20];
    printf("Password Strength Rules:\n");
printf("- More than 8 Characters\n");
printf("- Contains at least one Capital Letter (A-Z)\n");
printf("- Contains at least one Small Letter (a-z)\n");
printf("- Contains at least one Number (0-9)\n");
printf("- Contains at least one Symbol (!@#$%%)\n\n");
check_error:
    printf("Enter Your Password: ");
    fgets(password, sizeof(password), stdin);

    
    size_t password_length = strlen(password) - 1;
    printf("Your Password Length is %zu\n", password_length);


    
    bool has_min_length = false;
    int has_numbers = 0;
    int has_small_letters = 0;
    int has_symbols = 0;
    int has_capital_letters = 0;

    int passed_checks = 0;
    int total_checks = 5;
    bool input_error =false;

    
    if (password_length > 8){
        has_min_length =true;
        passed_checks++;
    }


   
    int i = 0;

check_characters:

    if (i < password_length){
        
        
        if (password[i] >= 'A' && password[i] <= 'Z'){
            passed_checks++;
            has_capital_letters++;
        } else if (password[i] >= 'a' && password[i] <= 'z'){
            passed_checks++;
            has_small_letters++;
        } else if (password[i] >= '0' && password[i] <= '9'){
            has_numbers++;
        } else if(password[i]=='@' || password[i]=='$' || password[i]=='%' || password[i]=='!'){
            // اذا ما كان حرف كبير ولا صغير ولا رقم، يعني أكيد رمز
            has_symbols++;
            passed_checks++;
        } else{
            input_error = true;
        }

        i++;
        goto check_characters; 
    }

    if(input_error){
        printf("Invalid Password! Spaces or invalid characters are not allowed. Please try again.\n\n");
        goto check_error;

    }
     

    float check_rate = ((float)passed_checks / total_checks) * 100.0f;

    
    bool is_password_strong = false;
    if(has_min_length  && has_symbols >0 && has_capital_letters >0 && has_small_letters >0 && has_numbers >0)
    is_password_strong =true;
    
    printf("Total Password Score is %d\n", passed_checks);
    printf("The Check Rate is %.2f\n", check_rate);
    printf("Is Password Strong ? %d\n", is_password_strong);


    if (is_password_strong){
        printf("Password is Strong\n");
    } else if (passed_checks >= 3){
        printf("Password is Medium\n");
    } else {
        printf("Password is Weak\n");
    }


    return 0;
}