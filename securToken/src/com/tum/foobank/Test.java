package com.tum.foobank;

import java.security.NoSuchAlgorithmException;

/**
 *
 */
public class Test {

    public static void main(String[] args) {
        try {
        	//System.out.print(TokenUtil.getCode("969172", "/var/www/html/foobank/exec/batchfile.txt"));
            System.out.print(TokenUtil.getCode("969172", "1", "5678"));
        } catch (NoSuchAlgorithmException e) {
            e.printStackTrace();
        } catch (Exception e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
    }

}
