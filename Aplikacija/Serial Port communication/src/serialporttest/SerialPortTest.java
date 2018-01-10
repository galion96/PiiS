/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package serialporttest;
import com.fazecast.jSerialComm.*;
import java.io.BufferedReader;
import java.io.DataOutputStream;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.io.OutputStream;
import static java.lang.Float.parseFloat;
import static java.lang.Integer.parseInt;
import java.math.BigDecimal;
import java.net.HttpURLConnection;
import java.net.URL;
import java.util.logging.Level;
import java.util.logging.Logger;
import static javafx.css.StyleOrigin.USER_AGENT;
import javax.net.ssl.HttpsURLConnection;
import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

/**
 *
 * @author dzelenika
 */
public class SerialPortTest {
public static BigDecimal round(float d, int decimalPlace) {
        BigDecimal bd = new BigDecimal(Float.toString(d));
        bd = bd.setScale(decimalPlace, BigDecimal.ROUND_HALF_UP);       
        return bd;
    }
  public static String clearString(String input){ //clears junk characters
     String output = "";
    for (int i=0; i<input.length(); i++) {
        if (input.charAt(i) <= 127&&input.charAt(i)!=':') {
            output += input.charAt(i);
        }
    }
    return output;
  
  }
  public static JSONObject  createJson(String[]tokens) throws JSONException{
      JSONObject jsonString = new JSONObject();
      jsonString.put("data",new JSONObject()
                .put("reading_value",round(parseFloat(tokens[4].split("&")[1]),2))
                .put("reading_count",parseInt(tokens[3])));
      jsonString.put("boardInfo",new JSONObject()
                .put("sensor_name",tokens[2])
                .put("serial_id",(tokens[1])));
  /*  JSONObject item= new JSONObject();
    JSONArray ja = new JSONArray();
     item.put("Value",round(parseFloat(tokens[4].split("&")[1]),2));
     item.put("Count",parseInt(tokens[3]));
     ja.put(item);
     jsonString.put("data",ja);
     item=new JSONObject();
     item.put("SensorName",tokens[2]);
     item.put("Node_id",(tokens[1]));
     ja=new JSONArray();
     ja.put(item);
      jsonString.put("boardInfo",ja);
   */


      return jsonString;
      
  }
  public static void funkcija() throws JSONException, IOException{
      JSONObject jo= new JSONObject();
jo.put("error","XBee module is unpluged");
sendPOST(jo.toString(),"http://localhost:2565/PiiS/error.php/");
  }
  public static boolean isValid(String data){
      if(data.contains("POC")&& data.contains("KRAJ")){
          return true;
      }
      return false;
  }
  public static SerialPort getPort(String portName){ //returns given port
      SerialPort [] comPort;
      comPort = SerialPort.getCommPorts();
int index=-1;
        for (int i = 0; i < comPort.length; i++) {
            if(comPort[i].getSystemPortName().compareTo(portName)==0){
            index=i;
            break;
        }}
 if(index!=-1){
    return comPort[index];
  }
 else{
     return null;
 }
  }
  public static String parse(String sensorOutput){
      if(sensorOutput.contains("POC")){
          int startIndex=sensorOutput.indexOf("POC");
            String data = sensorOutput.substring(startIndex);  
            return data;
      }

      return null;
  }
    public static void main(String[] args) throws JSONException, IOException {
SerialPort myPort = getPort("COM9");
if(myPort!=null){
myPort.openPort();
myPort.setBaudRate(115200);
System.out.println("I oppened " + myPort.getSystemPortName());
try {
   while (true)
   {
      while (myPort.bytesAvailable() == 0)
         Thread.sleep(20);
     
      byte[] readBuffer = new byte[myPort.bytesAvailable()];
      int numRead = myPort.readBytes(readBuffer, readBuffer.length);
      String incomingData=clearString(new String(readBuffer));
      incomingData=parse(incomingData);
      if(incomingData!=null && isValid(incomingData)){
     // System.out.println("Read " + numRead + " bytes." + " " +incomingData);
     String []values=incomingData.split("#");
     if(values.length>4){
         /* for (int i =1; i < values.length; i++) {
              System.out.print(i + " " + values[i]+ " ");
          }
          System.out.println()*/;
          JSONObject createJsonn = createJson(values);
       System.out.println(createJsonn.toString());
         // executePost("http://localhost/PiiS/nesto.php/",createJsonn.toString());
         sendPOST(createJsonn.toString(),"http://localhost:2565/PiiS/bulk.php");
      }
      }
      
   }
} catch (Exception e) { e.printStackTrace(); }
funkcija();
myPort.closePort();
    }}
    
    
    
    
    
private static void sendPOST(String POST_PARAMS, String url) throws IOException {
		URL obj = new URL(url);
		HttpURLConnection con = (HttpURLConnection) obj.openConnection();
		con.setRequestMethod("POST");
		//con.setRequestProperty("User-Agent", USER_AGENT);

		// For POST only - START
		con.setDoOutput(true);
		OutputStream os = con.getOutputStream();
		os.write(POST_PARAMS.getBytes());
		os.flush();
		os.close();
		// For POST only - END

		int responseCode = con.getResponseCode();
		System.out.println("POST Response Code :: " + responseCode);

		if (responseCode == HttpURLConnection.HTTP_OK) { //success
			BufferedReader in = new BufferedReader(new InputStreamReader(
					con.getInputStream()));
			String inputLine;
			StringBuffer response = new StringBuffer();

			while ((inputLine = in.readLine()) != null) {
				response.append(inputLine);
			}
			in.close();

			// print result
			System.out.println(response.toString());
		} else {
			System.out.println("POST request not worked");
		}
	}

}


    
