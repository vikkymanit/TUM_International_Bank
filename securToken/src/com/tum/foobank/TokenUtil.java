package com.tum.foobank;

import java.io.IOException;
import java.nio.charset.Charset;
import java.nio.file.Files;
import java.nio.file.Paths;
import java.security.MessageDigest;
import java.security.NoSuchAlgorithmException;

public class TokenUtil {

    private static final String DELIMITER = "@@";
    private static final long FIVE_MINUTES_MILLIS = 300000;

    /**
     * This function will be used by the Webapp to validate a user entered code
     *
     * @param clientPIN
     * @param amount
     * @param dstAccount
     * @param code
     * @return
     * @throws Exception
     */
    public static boolean isCodeValid(String clientPIN, String amount, String dstAccount, String code) throws Exception {
        long t = System.currentTimeMillis();
        long t1 = t-t%FIVE_MINUTES_MILLIS;
        long t2 = t1-FIVE_MINUTES_MILLIS;
        String s1 = null;
        String s2 = null;
        try {
            s1 = getCode(clientPIN, amount, dstAccount, t1);
            s2 = getCode(clientPIN, amount, dstAccount, t2);
        } catch (NoSuchAlgorithmException e) {
            //TODO: Use a logging framework here.
            System.out.print("Error NSE: Something went wrong. Please try again.");
            throw new Exception("Internal Error while processing.");
        } catch (RuntimeException e) {
            System.out.print("Error IE: Something went wrong. Please try again.");
            throw new Exception("Internal Error while processing.");
        }
        return s1.equals(code) || s2.equals(code);
    }

    /**
     * Called by the desktop application for generating the unique code
     *
     * @param clientPIN
     * @param amount
     * @param dstAccount
     * @return
     * @throws Exception
     */
    public static String getCode(String clientPIN, String amount, String dstAccount) throws Exception {
        long t = System.currentTimeMillis();
        try {
            return getCode(clientPIN, amount, dstAccount, t-t%FIVE_MINUTES_MILLIS);
        } catch (NoSuchAlgorithmException e) {
            //TODO: Use a logging framework here.
            System.out.print("Error NSE: Something went wrong. Please try again.");
            throw new Exception("Internal Error while processing.");
        } catch (RuntimeException e) {
            System.out.print("Error IE: Something went wrong. Please try again.");
            throw new Exception("Internal Error while processing.");
        }
    }
    
    /**
     * Called by the desktop application to generate a unique code from a batch file
     * 
     * @param clientPIN
     * @param fileName
     * @return
     * @throws Exception
     */
    public static String getCode(String clientPIN, String fileName) throws Exception {
        long t = System.currentTimeMillis();
        t=t-t%FIVE_MINUTES_MILLIS;
    	return getCode(clientPIN, fileName, t);
    }
    
    /**
     * Called by the PHP web app to check the validity of a code
     * 
     * @param clientPIN
     * @param fileName
     * @param code
     * @return
     * @throws Exception
     */
    public static boolean isCodeValid(String clientPIN, String fileName, String code) throws Exception {
    	long t = System.currentTimeMillis();
        long t1 = t-t%FIVE_MINUTES_MILLIS;
        long t2 = t1-FIVE_MINUTES_MILLIS;
        String s1 = getCode(clientPIN, fileName, t1);
        String s2 = getCode(clientPIN, fileName, t2);
        return s1.equals(code) || s2.equals(code);
    }
    
    private static String getCode(String clientPIN, String fileName, long t) throws Exception {
    	try {
			String fileContent = readFile(fileName);
			return concatAndGetCode(clientPIN, fileContent, t);
		} catch (IOException e) {
			System.out.print("Error IOE: Error while reading File.");
			e.printStackTrace();
			throw new Exception("Internal Error while processing");
		}
    }

    private static String concatAndGetCode(String clientPIN, String fileContent, long t) throws Exception {
    	try {
    		String result = clientPIN.concat(DELIMITER).concat(fileContent).concat(DELIMITER).concat(String.valueOf(t));
			return makeSHAHash(result);
		} catch (NoSuchAlgorithmException e) {
			System.out.print("Error NSE: No such algorithm exception. ");
			e.printStackTrace();
			throw new Exception("Internal Error while processing");
		}
    }
    
    private static String readFile (String fileName) throws IOException {
    	String fileContent = "";
    	for (String line: Files.readAllLines(Paths.get(fileName), Charset.forName("UTF-8"))) {
			fileContent = fileContent.concat(line);
		}
    	return fileContent;
    }
    
    private static String getCode(String clientPIN, String amount, String dstAccount, long t) throws NoSuchAlgorithmException {
        String s = concat(clientPIN, amount, dstAccount, String.valueOf(t));
        return makeSHAHash(s);
    }

    private static String makeSHAHash(String input) throws NoSuchAlgorithmException {
        MessageDigest md = MessageDigest.getInstance("SHA1");
        md.reset();
        byte[] buffer = input.getBytes();
        md.update(buffer);
        byte[] digest = md.digest();
        String hexStr = "";
        for (int i = 0; (i < digest.length && i < 5); i++) {
            hexStr +=  Integer.toString( ( digest[i] & 0xff ) + 0x100, 16).substring( 1 );
        }
        return hexStr;
    }

    private static String concat(String clientPIN, String amount, String dstAccount, String time) {
        StringBuilder s = new StringBuilder(clientPIN);
        s.append(DELIMITER);
        s.append(amount);
        s.append(DELIMITER);
        s.append(dstAccount);
        s.append(DELIMITER);
        s.append(time);
        return s.toString();
    }

    /**
     * Usage:
     * 1. java -jar jar_name pin amount account code - gives true or false if the code is valid
     * 2. java -jar jar_name pin filename code - gives true or false for the code generated
     *
     * @param args
     */
    public static void main(String[] args) {
        try {
            // System.out.println(args[0] + "@@" + args[1] + "@@" + args[2] + "@@" + args[3]);
        	if (args.length == 3) {
        		// batch file
                System.out.print(isCodeValid(args[0], args[1], args[2]));
            } else if (args.length == 4) {
            	// single transaction
                System.out.print(isCodeValid(args[0], args[1], args[2], args[3]));
              }  else if(args.length == 2) {
					System.out.print(getCode(args[0], args[1]));
				}
             else {
            	System.out.print("Invalid inputs");
            }
        } catch (Exception e) {
            e.printStackTrace();
        }
    }
}
