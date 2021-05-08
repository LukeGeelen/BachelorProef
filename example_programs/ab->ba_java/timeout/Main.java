import java.util.Scanner;

public class Main {

    public static void main(String[] args) {
	// write your code here
         Scanner input = new Scanner(System.in);
        
         try
            {
                Thread.sleep(1000);
            }
        catch(InterruptedException ex)
            {       
                Thread.currentThread().interrupt();
            }
         String a = input.nextLine();
         String b = input.nextLine();

         System.out.print(a + ' ');
         System.out.println(b);

         System.out.print(b + ' ');
         System.out.println(a);
    }
}
