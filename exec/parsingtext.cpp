#include <iostream>
using std::cout;
using std::endl;
using std::cin;
using std::ios;
using std::ifstream;
#include <cerrno>
#include <fstream>
#include <stdlib.h>
#include <cstring>
#include <string>
#include <sstream>
#include <limits.h>
#include <float.h>
using std::stringstream;
using std::string;

const int MAX_CHARS_PER_LINE = 50;
const int MAX_TOKENS_PER_LINE = 2;
const char*  DELIMITER = ",";

//Check for valid input

bool checktokenint(char* token){
	int i=0;
	while(token[i] != '\0'){
		if(!((isdigit(token[i]))))
			return false;
		i++;
	}
	
	return true;
}
bool checktokenfloat(char* token){
	int i=0,count=0;
	while(token[i] != '\0'){
		if(token[i] == '.'){
			count++;
			if(count>1)
				return false;
		}	
		if(!((isdigit(token[i])) || token[i] == '.'))
			return false;
		i++;
	}
	
	return true;
}

//Check for overflow

bool checkintoverflow(char* token){
	
	char* endtoken;
	errno = 0;
	strtol(token, &endtoken, 10);
	if ((endtoken != token && *endtoken != '\0') || errno == ERANGE)
		return false;
	return true;
}
bool checkfloatoverflow(char* token){
	
	char* endtoken;
	errno = 0;
	strtof(token, &endtoken);
	if ((endtoken != token && *endtoken != '\0') || errno == ERANGE)
		return false;
	return true;
}
bool checksumoverflow(float sum, float sumtemp){
	double res = sum + sumtemp;
	if (res > FLT_MAX)
			return false;
	return true;
}

int main(int argc, char * ARGV [])
{
	  float sum = 0;
	  int dataflag = 0;
	  stringstream ss,file_path,errorstream;
	  ss << "{\"transactions\": [";
	  string jsontemp,jsontrans,json,file,error;
	  ifstream fin;
	  file_path << ARGV[1];
	  //file_path << "data.txt";
	  file = file_path.str();
	  fin.open(file.c_str()); // open the file
	  if (!fin.good()) 
	    return 0; // exit if file not found
	  
	  // read each line of the file
	  while (!fin.eof())
	  {		  
		    // read an entire line into memory
		    char buf[MAX_CHARS_PER_LINE];
		    fin.getline(buf, MAX_CHARS_PER_LINE);
		    
			int n = 0; 
		    // array to store memory addresses of the tokens in buf
		    char* token[MAX_TOKENS_PER_LINE] = {}; // initialize to 0
		    
		    // parse the line
		    token[0] = strtok(buf, DELIMITER); // first token
		    
		    if (token[0]) // zero if line is blank
		    {
			      if(!checktokenint(token[0])){
						cout << "Invalid file";
						return 0;
					}
			      for (n = 1; n < MAX_TOKENS_PER_LINE; n++)
			      {
				        token[n] = strtok(0, DELIMITER); // subsequent tokens
				        if (!token[n]) {   // no more tokens
							cout << "Invalid file";
							return 0;							
						}; 
				        if(!checktokenfloat(token[n])){
							cout << "Invalid file";
							return 0;
						}
						dataflag = 1;				        
			      }
		    }
			else
			{
				continue;
			}
			
			if(!(checkintoverflow(token[0]))) {
				cout << "Invalid file";
				exit(1);
			}
			
			if(!((checkintoverflow(token[1])) || (checkfloatoverflow(token[1])))) {
				cout << "Invalid file";
				exit(1);
			}
			
		
			cout.setf(ios::fixed, ios::floatfield);
			cout.setf(ios::showpoint);
			//Construct a Json string 
			ss << "{\"destacc\":" << token[0] << ",\"amount\":" << token[1] << "}, ";
			double sumtemp = strtof(token[1],NULL);
			
			if(!(checksumoverflow(sum,sumtemp))) {
				cout << "Invalid file";
				exit(1);
			}
			
			sum = sum + sumtemp;	

	  }
	  
	  if(dataflag){
			jsontemp = ss.str();
			jsontrans = jsontemp.substr(0,jsontemp.length()-2) + "],";    	//remove the last character ","
			ss.str(string());  												//free the string stream
			ss << jsontrans << "\"sum\":" << sum << "}";					//include sum of the transaction amount in the json string
			json = ss.str();
			cout << json;
	}
	else
		cout << "Invalid file";
		
  return 1;
  
}


