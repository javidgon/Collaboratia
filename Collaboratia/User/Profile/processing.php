<?php
session_start();
@include("../../Functions/db/user_db.php");
@include("../../Functions/db/project_db.php");
@include("../../Functions/authentification/user_auth.php");
if (isset($_SESSION['email'])) {
?>
    <html>
        <head>
            <style type="text/css">
                @import "../../CSS/index.css";
                @import "../../CSS/buttons.css";
                @import "../../CSS/texts.css";
                @import "../../CSS/menus.css";
                @import "../../CSS/alerts.css";
            </style>
            <title>Processing</title>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        </head>
        <body>
            <div class="header">
                <img style="float:right;margin-right:11em; "src="../../Img/logo.png" width="150" alt="Imagen" />
            <? if (isset($_SESSION['email'])) {
            ?><p>Welcome

                <?
                echo '<b>' . $_SESSION['email'] . '</b> </p>';
            }
                ?>

        </div>
        <ul id="menu-horizontal">
            <li><a href="../index.php" title="Texto">My Page</a>
                <ul>
                    <li><a href="index.php" title="Texto">My Profile</a></li>
                    <li><a href="../myProjects.php" title="Texto">My Projects</a></li>
                </ul></li>
            <li><a href="../collaborative.php" title="Texto">Collaborative Projects</a>

            </li>
            <li><a href="../create.php" title="Texto">Create New Project</a></li>

        </ul>
        <ul id="menu-horizontal2">
            <li><a href="index.php" title="Back">Back</a>
        </ul>
        <div class="exit">
            <form action="../../index.php" method="post">
                <input type="hidden" name= "end" value="end"/>
                <div class="buttons">
                    <button type="submit" class="positive">
                        <img src="../../Img/icons/application_side_contract.png" alt=""/>
                        Exit
                    </button>
                </div>
            </form>

        </div>


        <?
            if (isset($_POST['save'])) {
                if ($_POST['name'] == "") {
                    $errors[] = 'You didn\'t enter "name" value properly.';
                }
                if ($_POST['surname'] == "") {

                    $errors[] = 'You didn\'t enter "participants" value properly.';
                }
                if (strlen($_POST['name']) > 50 || strlen($_POST['surname'] > 50)) {
                    $errors[] = 'Check the length for your "name" and "surname".';
                }
                if ($_POST['university'] == "0") {

                    $errors[] = 'You didn\'t enter "type" value properly.';
                }
                if (strlen($_POST['university']) > 50) {
                    $errors[] = 'Your university length is more than 50 characters.';
                }
                if ($_POST['dateOfBirth_Day'] == 0 || $_POST['dateOfBirth_Year'] == 0
                        || strcmp($_POST['dateOfBirth_Month'], "") == 0) {
                    $errors[] = 'You\'ve selected a wrong "date"';
                }
                if ($_POST['country'] == "0") {

                    $errors[] = 'You didn\'t enter "type" value properly.';
                }
                if ($_POST['password'] == "") {

                    $errors[] = 'You didn\'t enter "password" value properly.';
                }

                if ($_POST['repassword'] == "") {

                    $errors[] = 'You didn\'t enter "re-password" value properly.';
                }
                if (strlen($_POST['password']) < 8) {
                    $errors[] = 'Your password can\'t have less than 8 characters.';
                }
                if ($_POST['field'] == "0") {

                    $errors[] = 'You didn\'t enter "field" value properly.';
                }
                if ($_POST['password'] != $_POST['repassword']) {
                    $errors[] = 'Your passwords didn\'t match';
                }

                if ($_FILES['picture']['name'] != "") {
                    //Comprobamos errores en la subida de la foto.
                    if ($_FILES['picture']['error'] > 0) {
                        $errors[] = 'There\'s an error during picture processing.';
                    }
                    //Comprobamos que el tipo del fichero sea permitido.
                    if (onlyImages($_FILES['picture']) == false) {
                        $errors[] = 'You\'ve introduced an invalid type of file.';
                    }
                    //Si la foto supera el peso permitido.
                    if (sizeLimit($_FILES['picture'], 1024 * 1024) == false) {
                        $errors[] = 'The picture size is greater than the limit.';
                    }
                }


                $args = array(
                    'name' => FILTER_SANITIZE_MAGIC_QUOTES,
                    'surname' => FILTER_SANITIZE_MAGIC_QUOTES,
                    'university' => FILTER_SANITIZE_MAGIC_QUOTES,
                    'password' => FILTER_SANITIZE_MAGIC_QUOTES,
                    'repassword' => FILTER_SANITIZE_MAGIC_QUOTES,
                    'field' => FILTER_SANITIZE_MAGIC_QUOTES,
                    'country' => FILTER_SANITIZE_MAGIC_QUOTES
                );

                $myinputs = filter_input_array(INPUT_POST, $args);

                if (array_search(false, $myinputs, true) || array_search(NULL, $myinputs, true)) {
                    $errors[] = 'There\'re some errors on the form.';
                }

                if (!isset($errors)) { // Si no hay errores.
                    $name = filter_var($myinputs['name'], FILTER_SANITIZE_STRING); // Eliminamos las etiquetas html.
                    $surname = filter_var($myinputs['surname'], FILTER_SANITIZE_STRING); // Eliminamos las etiquetas html.
                    $university = filter_var($myinputs['university'], FILTER_SANITIZE_STRING); // Eliminamos las etiquetas html.
                    $password = filter_var($myinputs['password'], FILTER_SANITIZE_STRING); // Eliminamos las etiquetas html.
                    $repassword = filter_var($myinputs['repassword'], FILTER_SANITIZE_STRING); // Eliminamos las etiquetas html.
                    $dateOfBirth_Day = $_POST['dateOfBirth_Day'];
                    $dateOfBirth_Month = $_POST['dateOfBirth_Month'];
                    $dateOfBirth_Year = $_POST['dateOfBirth_Year'];
                    $field = $_POST['field'];
                    $university = filter_var($myinputs['university'], FILTER_SANITIZE_STRING);
                    $country = $_POST['country'];

                    //Capturamos la extensión del archivo.
                    $ext = substr($_FILES['picture']['name'], strrpos($_FILES['picture']['name'], '.'));

                    // Creamos el proyecto.
                    $res = updateUser('localhost', $_SESSION['email'], $password, $name, $surname, $dateOfBirth_Day, $dateOfBirth_Month, $dateOfBirth_Year, $field, $university, $country, $_SESSION['email'] . $ext);

                    if ($res != true) {
                        echo 'Error a la hora de crear el proyecto.';
                    } else {
                        echo '<div class="infoPositive">Congratulations! Your user has been updated.</div>';
                        if ($_FILES['picture']['name'] != "") {
                            //Guardamos la foto en el sistema.
                            $ruta = "../../Img/users/" . $_SESSION['email'] . $ext;
                            unlink($ruta);
                            move_uploaded_file($_FILES['picture']['tmp_name'], $ruta);
                        }
                    }
                } else {  // Si hay errores.
                    foreach ($errors as $indice => $error) {
                        echo "<p class=\"infoNegativeError\">$error</p>";   // Vamos mostrando los errores.
                    }
                }
            }
            if (isset($_POST['edit'])) {
                $info = retrieveUser('localhost', $_SESSION['email']);
                $row = mysql_fetch_array($info);
        ?>
                <div class="title">
                    <h1>Edit profile</h1>
                    <form action="processing.php" method="post" enctype="multipart/form-data">
                        <table>
                            <tr>
                                <th>Password: </th>
                                <td><input type="password" name="password" value=""/></td>
                                <th>Re-Password: </th>
                                <td><input type="password" name="repassword" value=""/></td>
                            </tr>
                            <tr>
                                <th>Name: </th>
                                <td><input type="text" name="name" value="<? echo $row[1]; ?>"/></td>
                                <th>Date of Birth: </th>
                                <td><select name="dateOfBirth_Month">
                                        <option value="0"> - Month - </option>
                                        <option value="January">January</option>
                                        <option value="Febuary">Febuary</option>
                                        <option value="March">March</option>
                                        <option value="April">April</option>
                                        <option value="May">May</option>
                                        <option value="June">June</option>
                                        <option value="July">July</option>
                                        <option value="August">August</option>
                                        <option value="September">September</option>
                                        <option value="October">October</option>
                                        <option value="November">November</option>
                                        <option value="December">December</option>
                                    </select>

                                    <select name="dateOfBirth_Day">
                                        <option value="0"> - Day - </option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                        <option value="7">7</option>
                                        <option value="8">8</option>
                                        <option value="9">9</option>
                                        <option value="10">10</option>
                                        <option value="11">11</option>
                                        <option value="12">12</option>
                                        <option value="13">13</option>
                                        <option value="14">14</option>
                                        <option value="15">15</option>
                                        <option value="16">16</option>
                                        <option value="17">17</option>
                                        <option value="18">18</option>
                                        <option value="19">19</option>
                                        <option value="20">20</option>
                                        <option value="21">21</option>
                                        <option value="22">22</option>
                                        <option value="23">23</option>
                                        <option value="24">24</option>
                                        <option value="25">25</option>
                                        <option value="26">26</option>
                                        <option value="27">27</option>
                                        <option value="28">28</option>
                                        <option value="29">29</option>
                                        <option value="30">30</option>
                                        <option value="31">31</option>
                                    </select>

                                    <select name="dateOfBirth_Year">
                                        <option value="0"> - Year - </option>
                                        <option value="2004">2004</option>
                                        <option value="2003">2003</option>
                                        <option value="2002">2002</option>
                                        <option value="2001">2001</option>
                                        <option value="2000">2000</option>
                                        <option value="1999">1999</option>
                                        <option value="1998">1998</option>
                                        <option value="1997">1997</option>
                                        <option value="1996">1996</option>
                                        <option value="1995">1995</option>
                                        <option value="1994">1994</option>
                                        <option value="1993">1993</option>
                                        <option value="1992">1992</option>
                                        <option value="1991">1991</option>
                                        <option value="1990">1990</option>
                                        <option value="1989">1989</option>
                                        <option value="1988">1988</option>
                                        <option value="1987">1987</option>
                                        <option value="1986">1986</option>
                                        <option value="1985">1985</option>
                                        <option value="1984">1984</option>
                                        <option value="1983">1983</option>
                                        <option value="1982">1982</option>
                                        <option value="1981">1981</option>
                                        <option value="1980">1980</option>
                                        <option value="1979">1979</option>
                                        <option value="1978">1978</option>
                                        <option value="1977">1977</option>
                                        <option value="1976">1976</option>
                                        <option value="1975">1975</option>
                                        <option value="1974">1974</option>
                                        <option value="1973">1973</option>
                                        <option value="1972">1972</option>
                                        <option value="1971">1971</option>
                                        <option value="1970">1970</option>
                                        <option value="1969">1969</option>
                                        <option value="1968">1968</option>
                                        <option value="1967">1967</option>
                                        <option value="1966">1966</option>
                                        <option value="1965">1965</option>
                                        <option value="1964">1964</option>
                                        <option value="1963">1963</option>
                                        <option value="1962">1962</option>
                                        <option value="1961">1961</option>
                                        <option value="1960">1960</option>
                                        <option value="1959">1959</option>
                                        <option value="1958">1958</option>
                                        <option value="1957">1957</option>
                                        <option value="1956">1956</option>
                                        <option value="1955">1955</option>
                                        <option value="1954">1954</option>
                                        <option value="1953">1953</option>
                                        <option value="1952">1952</option>
                                        <option value="1951">1951</option>
                                        <option value="1950">1950</option>
                                        <option value="1949">1949</option>
                                        <option value="1948">1948</option>
                                        <option value="1947">1947</option>
                                        <option value="1946">1946</option>
                                        <option value="1945">1945</option>
                                        <option value="1944">1944</option>
                                        <option value="1943">1943</option>
                                        <option value="1942">1942</option>
                                        <option value="1941">1941</option>
                                        <option value="1940">1940</option>
                                        <option value="1939">1939</option>
                                        <option value="1938">1938</option>
                                        <option value="1937">1937</option>
                                        <option value="1936">1936</option>
                                        <option value="1935">1935</option>
                                        <option value="1934">1934</option>
                                        <option value="1933">1933</option>
                                        <option value="1932">1932</option>
                                        <option value="1931">1931</option>
                                        <option value="1930">1930</option>
                                        <option value="1929">1929</option>
                                        <option value="1928">1928</option>
                                        <option value="1927">1927</option>
                                        <option value="1926">1926</option>
                                        <option value="1925">1925</option>
                                        <option value="1924">1924</option>
                                        <option value="1923">1923</option>
                                        <option value="1922">1922</option>
                                        <option value="1921">1921</option>
                                        <option value="1920">1920</option>
                                        <option value="1919">1919</option>
                                        <option value="1918">1918</option>
                                        <option value="1917">1917</option>
                                        <option value="1916">1916</option>
                                        <option value="1915">1915</option>
                                        <option value="1914">1914</option>
                                        <option value="1913">1913</option>
                                        <option value="1912">1912</option>
                                        <option value="1911">1911</option>
                                        <option value="1910">1910</option>
                                        <option value="1909">1909</option>
                                        <option value="1908">1908</option>
                                        <option value="1907">1907</option>
                                        <option value="1906">1906</option>
                                        <option value="1905">1905</option>
                                        <option value="1904">1904</option>
                                        <option value="1903">1903</option>
                                        <option value="1902">1902</option>
                                        <option value="1901">1901</option>
                                        <option value="1900">1900</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>Surname: </th>
                                <td><input type="text" name="surname" value="<? echo $row[2]; ?>"/></td>
                                <th>Field: </th>
                                <td><select name="field">
                                        <option value="0">Select Field</option>
                                        <option value="Computer Science">Computer Science</option>
                                        <option value="A. I.">A. I.</option>
                                        <option value="Biology">Biology</option>
                                        <option value="Bio-Technology">Bio-Technology</option>
                                        <option value="Networks">Networks</option>
                                        <option value="Communications">Communications</option>
                                        <option value="Industrial">Industrial</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>University: </th>
                                <td><input type="text" name="university"  value="<? echo $row[10]; ?>"/></td>
                                <th>Country: </th>
                                <td>
                                    <select name="country">
                                        <option value="0" selected="selected">Select Country</option>
                                        <option value="United States">United States</option>
                                        <option value="United Kingdom">United Kingdom</option>
                                        <option value="Afghanistan">Afghanistan</option>
                                        <option value="Albania">Albania</option>
                                        <option value="Algeria">Algeria</option>
                                        <option value="American Samoa">American Samoa</option>
                                        <option value="Andorra">Andorra</option>
                                        <option value="Angola">Angola</option>
                                        <option value="Anguilla">Anguilla</option>
                                        <option value="Antarctica">Antarctica</option>
                                        <option value="Antigua and Barbuda">Antigua and Barbuda</option>
                                        <option value="Argentina">Argentina</option>
                                        <option value="Armenia">Armenia</option>
                                        <option value="Aruba">Aruba</option>
                                        <option value="Australia">Australia</option>
                                        <option value="Austria">Austria</option>
                                        <option value="Azerbaijan">Azerbaijan</option>
                                        <option value="Bahamas">Bahamas</option>
                                        <option value="Bahrain">Bahrain</option>
                                        <option value="Bangladesh">Bangladesh</option>
                                        <option value="Barbados">Barbados</option>
                                        <option value="Belarus">Belarus</option>
                                        <option value="Belgium">Belgium</option>
                                        <option value="Belize">Belize</option>
                                        <option value="Benin">Benin</option>
                                        <option value="Bermuda">Bermuda</option>
                                        <option value="Bhutan">Bhutan</option>
                                        <option value="Bolivia">Bolivia</option>
                                        <option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
                                        <option value="Botswana">Botswana</option>
                                        <option value="Bouvet Island">Bouvet Island</option>
                                        <option value="Brazil">Brazil</option>
                                        <option value="British Indian Ocean Territory">British Indian Ocean Territory</option>
                                        <option value="Brunei Darussalam">Brunei Darussalam</option>
                                        <option value="Bulgaria">Bulgaria</option>
                                        <option value="Burkina Faso">Burkina Faso</option>
                                        <option value="Burundi">Burundi</option>
                                        <option value="Cambodia">Cambodia</option>
                                        <option value="Cameroon">Cameroon</option>
                                        <option value="Canada">Canada</option>
                                        <option value="Cape Verde">Cape Verde</option>
                                        <option value="Cayman Islands">Cayman Islands</option>
                                        <option value="Central African Republic">Central African Republic</option>
                                        <option value="Chad">Chad</option>
                                        <option value="Chile">Chile</option>
                                        <option value="China">China</option>
                                        <option value="Christmas Island">Christmas Island</option>
                                        <option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option>
                                        <option value="Colombia">Colombia</option>
                                        <option value="Comoros">Comoros</option>
                                        <option value="Congo">Congo</option>
                                        <option value="Congo, The Democratic Republic of The">Congo, The Democratic Republic of The</option>
                                        <option value="Cook Islands">Cook Islands</option>
                                        <option value="Costa Rica">Costa Rica</option>
                                        <option value="Cote D\'ivoire">Cote D\'ivoire</option>
                                        <option value="Croatia">Croatia</option>
                                        <option value="Cuba">Cuba</option>
                                        <option value="Cyprus">Cyprus</option>
                                        <option value="Czech Republic">Czech Republic</option>
                                        <option value="Denmark">Denmark</option>
                                        <option value="Djibouti">Djibouti</option>
                                        <option value="Dominica">Dominica</option>
                                        <option value="Dominican Republic">Dominican Republic</option>
                                        <option value="Ecuador">Ecuador</option>
                                        <option value="Egypt">Egypt</option>
                                        <option value="El Salvador">El Salvador</option>
                                        <option value="Equatorial Guinea">Equatorial Guinea</option>
                                        <option value="Eritrea">Eritrea</option>
                                        <option value="Estonia">Estonia</option>
                                        <option value="Ethiopia">Ethiopia</option>
                                        <option value="Falkland Islands (Malvinas)">Falkland Islands (Malvinas)</option>
                                        <option value="Faroe Islands">Faroe Islands</option>
                                        <option value="Fiji">Fiji</option>
                                        <option value="Finland">Finland</option>
                                        <option value="France">France</option>
                                        <option value="French Guiana">French Guiana</option>
                                        <option value="French Polynesia">French Polynesia</option>
                                        <option value="French Southern Territories">French Southern Territories</option>
                                        <option value="Gabon">Gabon</option>
                                        <option value="Gambia">Gambia</option>
                                        <option value="Georgia">Georgia</option>
                                        <option value="Germany">Germany</option>
                                        <option value="Ghana">Ghana</option>
                                        <option value="Gibraltar">Gibraltar</option>
                                        <option value="Greece">Greece</option>
                                        <option value="Greenland">Greenland</option>
                                        <option value="Grenada">Grenada</option>
                                        <option value="Guadeloupe">Guadeloupe</option>
                                        <option value="Guam">Guam</option>
                                        <option value="Guatemala">Guatemala</option>
                                        <option value="Guinea">Guinea</option>
                                        <option value="Guinea-bissau">Guinea-bissau</option>
                                        <option value="Guyana">Guyana</option>
                                        <option value="Haiti">Haiti</option>
                                        <option value="Heard Island and Mcdonald Islands">Heard Island and Mcdonald Islands</option>
                                        <option value="Holy See (Vatican City State)">Holy See (Vatican City State)</option>
                                        <option value="Honduras">Honduras</option>
                                        <option value="Hong Kong">Hong Kong</option>
                                        <option value="Hungary">Hungary</option>
                                        <option value="Iceland">Iceland</option>
                                        <option value="India">India</option>
                                        <option value="Indonesia">Indonesia</option>
                                        <option value="Iran, Islamic Republic of">Iran, Islamic Republic of</option>
                                        <option value="Iraq">Iraq</option>
                                        <option value="Ireland">Ireland</option>
                                        <option value="Israel">Israel</option>
                                        <option value="Italy">Italy</option>
                                        <option value="Jamaica">Jamaica</option>
                                        <option value="Japan">Japan</option>
                                        <option value="Jordan">Jordan</option>
                                        <option value="Kazakhstan">Kazakhstan</option>
                                        <option value="Kenya">Kenya</option>
                                        <option value="Kiribati">Kiribati</option>
                                        <option value="Korea, Democratic People\'s Republic of">Korea, Democratic People\'s Republic of</option>
                                        <option value="Korea, Republic of">Korea, Republic of</option>
                                        <option value="Kuwait">Kuwait</option>
                                        <option value="Kyrgyzstan">Kyrgyzstan</option>
                                        <option value="Lao People\'s Democratic Republic">Lao People\'s Democratic Republic</option>
                                        <option value="Latvia">Latvia</option>
                                        <option value="Lebanon">Lebanon</option>
                                        <option value="Lesotho">Lesotho</option>
                                        <option value="Liberia">Liberia</option>
                                        <option value="Libyan Arab Jamahiriya">Libyan Arab Jamahiriya</option>
                                        <option value="Liechtenstein">Liechtenstein</option>
                                        <option value="Lithuania">Lithuania</option>
                                        <option value="Luxembourg">Luxembourg</option>
                                        <option value="Macao">Macao</option>
                                        <option value="Macedonia, The Former Yugoslav Republic of">Macedonia, The Former Yugoslav Republic of</option>
                                        <option value="Madagascar">Madagascar</option>
                                        <option value="Malawi">Malawi</option>
                                        <option value="Malaysia">Malaysia</option>
                                        <option value="Maldives">Maldives</option>
                                        <option value="Mali">Mali</option>
                                        <option value="Malta">Malta</option>
                                        <option value="Marshall Islands">Marshall Islands</option>
                                        <option value="Martinique">Martinique</option>
                                        <option value="Mauritania">Mauritania</option>
                                        <option value="Mauritius">Mauritius</option>
                                        <option value="Mayotte">Mayotte</option>
                                        <option value="Mexico">Mexico</option>
                                        <option value="Micronesia, Federated States of">Micronesia, Federated States of</option>
                                        <option value="Moldova, Republic of">Moldova, Republic of</option>
                                        <option value="Monaco">Monaco</option>
                                        <option value="Mongolia">Mongolia</option>
                                        <option value="Montserrat">Montserrat</option>
                                        <option value="Morocco">Morocco</option>
                                        <option value="Mozambique">Mozambique</option>
                                        <option value="Myanmar">Myanmar</option>
                                        <option value="Namibia">Namibia</option>
                                        <option value="Nauru">Nauru</option>
                                        <option value="Nepal">Nepal</option>
                                        <option value="Netherlands">Netherlands</option>
                                        <option value="Netherlands Antilles">Netherlands Antilles</option>
                                        <option value="New Caledonia">New Caledonia</option>
                                        <option value="New Zealand">New Zealand</option>
                                        <option value="Nicaragua">Nicaragua</option>
                                        <option value="Niger">Niger</option>
                                        <option value="Nigeria">Nigeria</option>
                                        <option value="Niue">Niue</option>
                                        <option value="Norfolk Island">Norfolk Island</option>
                                        <option value="Northern Mariana Islands">Northern Mariana Islands</option>
                                        <option value="Norway">Norway</option>
                                        <option value="Oman">Oman</option>
                                        <option value="Pakistan">Pakistan</option>
                                        <option value="Palau">Palau</option>
                                        <option value="Palestinian Territory, Occupied">Palestinian Territory, Occupied</option>
                                        <option value="Panama">Panama</option>
                                        <option value="Papua New Guinea">Papua New Guinea</option>
                                        <option value="Paraguay">Paraguay</option>
                                        <option value="Peru">Peru</option>
                                        <option value="Philippines">Philippines</option>
                                        <option value="Pitcairn">Pitcairn</option>
                                        <option value="Poland">Poland</option>
                                        <option value="Portugal">Portugal</option>
                                        <option value="Puerto Rico">Puerto Rico</option>
                                        <option value="Qatar">Qatar</option>
                                        <option value="Reunion">Reunion</option>
                                        <option value="Romania">Romania</option>
                                        <option value="Russian Federation">Russian Federation</option>
                                        <option value="Rwanda">Rwanda</option>
                                        <option value="Saint Helena">Saint Helena</option>
                                        <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
                                        <option value="Saint Lucia">Saint Lucia</option>
                                        <option value="Saint Pierre and Miquelon">Saint Pierre and Miquelon</option>
                                        <option value="Saint Vincent and The Grenadines">Saint Vincent and The Grenadines</option>
                                        <option value="Samoa">Samoa</option>
                                        <option value="San Marino">San Marino</option>
                                        <option value="Sao Tome and Principe">Sao Tome and Principe</option>
                                        <option value="Saudi Arabia">Saudi Arabia</option>
                                        <option value="Senegal">Senegal</option>
                                        <option value="Serbia and Montenegro">Serbia and Montenegro</option>
                                        <option value="Seychelles">Seychelles</option>
                                        <option value="Sierra Leone">Sierra Leone</option>
                                        <option value="Singapore">Singapore</option>
                                        <option value="Slovakia">Slovakia</option>
                                        <option value="Slovenia">Slovenia</option>
                                        <option value="Solomon Islands">Solomon Islands</option>
                                        <option value="Somalia">Somalia</option>
                                        <option value="South Africa">South Africa</option>
                                        <option value="South Georgia and The South Sandwich Islands">South Georgia and The South Sandwich Islands</option>
                                        <option value="Spain">Spain</option>
                                        <option value="Sri Lanka">Sri Lanka</option>
                                        <option value="Sudan">Sudan</option>
                                        <option value="Suriname">Suriname</option>
                                        <option value="Svalbard and Jan Mayen">Svalbard and Jan Mayen</option>
                                        <option value="Swaziland">Swaziland</option>
                                        <option value="Sweden">Sweden</option>
                                        <option value="Switzerland">Switzerland</option>
                                        <option value="Syrian Arab Republic">Syrian Arab Republic</option>
                                        <option value="Taiwan, Province of China">Taiwan, Province of China</option>
                                        <option value="Tajikistan">Tajikistan</option>
                                        <option value="Tanzania, United Republic of">Tanzania, United Republic of</option>
                                        <option value="Thailand">Thailand</option>
                                        <option value="Timor-leste">Timor-leste</option>
                                        <option value="Togo">Togo</option>
                                        <option value="Tokelau">Tokelau</option>
                                        <option value="Tonga">Tonga</option>
                                        <option value="Trinidad and Tobago">Trinidad and Tobago</option>
                                        <option value="Tunisia">Tunisia</option>
                                        <option value="Turkey">Turkey</option>
                                        <option value="Turkmenistan">Turkmenistan</option>
                                        <option value="Turks and Caicos Islands">Turks and Caicos Islands</option>
                                        <option value="Tuvalu">Tuvalu</option>
                                        <option value="Uganda">Uganda</option>
                                        <option value="Ukraine">Ukraine</option>
                                        <option value="United Arab Emirates">United Arab Emirates</option>
                                        <option value="United Kingdom">United Kingdom</option>
                                        <option value="United States">United States</option>
                                        <option value="United States Minor Outlying Islands">United States Minor Outlying Islands</option>
                                        <option value="Uruguay">Uruguay</option>
                                        <option value="Uzbekistan">Uzbekistan</option>
                                        <option value="Vanuatu">Vanuatu</option>
                                        <option value="Venezuela">Venezuela</option>
                                        <option value="Viet Nam">Viet Nam</option>
                                        <option value="Virgin Islands, British">Virgin Islands, British</option>
                                        <option value="Virgin Islands, U.S.">Virgin Islands, U.S.</option>
                                        <option value="Wallis and Futuna">Wallis and Futuna</option>
                                        <option value="Western Sahara">Western Sahara</option>
                                        <option value="Yemen">Yemen</option>
                                        <option value="Zambia">Zambia</option>
                                        <option value="Zimbabwe">Zimbabwe</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>Picture: </th>
                                <td><input type="file" name="picture" /></td>
                            </tr>
                            <tr><td colspan="4"></td></tr>
                            <tr><div class="buttonsLeft">
                                <form action="processing.php" method="post">
                                    <td class="buttonsLeft" colspan="2"><button type="submit" name="save" class="positive">
                                            <img src="../../Img/icons/application_edit.png" alt=""/>
                                            Save
                                        </button></td>


                                    <td class="buttonsLeft" colspan="2"><button type="reset"class="negative">
                                            <img src="../../Img/icons/application_delete.png" alt=""/>
                                            Reset
                                        </button></td>
                                </form>
                            </div></tr>
                        </table>
                    </form>
                </div>
        <?php
            }
            if (isset($_POST['delete'])) { // Si hemos pulsado el botón delete.
        ?>
                <div class="title">
                    <p>Are you sure you want to delete your account?</p>

                    <table>
                        <tr>
                        <form action="../../end.php" method="post">
                            <td class="buttonsLeft"><button type="submit" name="delete" value="delete"class="positive">
                                    <img src="../../Img/icons/accept.png" alt=""/>
                                    Yes
                                </button></td>
                        </form>
                        <form action="index.php" method="post">
                            <td class="buttonsLeft"><button type="submit" name="no" class="negative">
                                    <img src="../../Img/icons/book_delete.png" alt=""/>
                                    No
                                </button></td>
                        </form>
                        </tr>
                    </table>
                </div>
        <?php
            }
        } ?>
    </body>
</html>