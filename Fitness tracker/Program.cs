using System;
using MySql.Data.MySqlClient;
using System.Collections.Generic;

namespace Fitness_tracker
{
    class Track
    {
        public int kg {get; set;}
        public int rep { get; set; }

        public bool PushDay { get; set; }
        public bool PullDay { get; set; }




        public Track(string dayType)
        {
            if (dayType == "push")
            {
                PushDay = true;
                PullDay = false;
            }
            else if (dayType == "pull")
            {
                PushDay = false;
                PullDay = true;
            }
        }
        public void AddTrack(string name, string exercise, int set_nr, int rep, int kg)
        {
            DateTime dato = DateTime.Now.Date;
            var con = GetConnection();
            MySqlCommand cmd = new MySqlCommand();
            cmd.Connection = con;
            cmd.CommandText = "insert into fitness values (@name, @øvelse, @set_nr, @rep, @kg, @dato)";
            cmd.Prepare();
            cmd.Parameters.AddWithValue("@name", name);
            cmd.Parameters.AddWithValue("@øvelse", exercise);
            cmd.Parameters.AddWithValue("@set_nr", set_nr);
            cmd.Parameters.AddWithValue("@rep", rep);
            cmd.Parameters.AddWithValue("@kg", kg);
            cmd.Parameters.AddWithValue("@dato", dato);
            cmd.ExecuteNonQuery();
            if (con != null) { con.Close(); }
        }

        public MySqlConnection GetConnection()
        {
            string conStr = "server=your-server;" +
                "uid=your-user;" +
                "pwd=your-password;" +
                "database=your-database";

            var con = new MySqlConnection();
            con.ConnectionString = conStr;
            con.Open();
            return con;
        }

        public void UpdateRepCount()
        {
            string input = Console.ReadLine();
            string[] inputArray = input.Split(" ");

            if (inputArray.Length == 1)
            {
                try { rep = Convert.ToInt32(inputArray[0]); }
                catch (Exception e)
                { rep = 0; }
            }
            else if (inputArray.Length == 2)
            {
                rep = Convert.ToInt32(inputArray[0]);
                kg = Convert.ToInt32(inputArray[1]);
            }
            else
            {
                Console.WriteLine("Prøv igen");
                UpdateRepCount();
            }

        }
        //
        public void Lift()
        {
            List<string> brothers = new List<string>();
            brothers.Add("Thomas");
            brothers.Add("Morten");
            brothers.Add("Andreas");
            brothers.Add("Niels");

            List<string> Exercises = new List<string>();

            if(PushDay)
            {
                Exercises.Add("Dumbell chest press");
                Exercises.Add("Armbøjninger");
                Exercises.Add("Shoulder press");
            }
            else
            {
                Exercises.Add("Rygøvelse");
                Exercises.Add("Standing bicep curls");
                Exercises.Add("Sitting bicep curls");
            }


            foreach (var exercise in Exercises)
            {
                for (int set = 1; set < 4; set++)
                {
                    foreach (var brother in brothers)
                    {
                        Console.WriteLine($"\n\n\n\n\n{brother} - {exercise} - Set {set}");
                        UpdateRepCount();
                        AddTrack(brother, exercise, set, rep, kg);
                    }
                    
                }
            }

        }

        static void Main(string[] args)
        {
            Console.WriteLine("Skriv \"push\" eller \"pull\"");
            string userinput = Console.ReadLine();

            try
            {
                Track test = new Track(userinput);
                test.Lift();
            }
            catch (Exception e)
            {
                Console.WriteLine(e);
            }
            
            Console.WriteLine("færdiiiigg");
        }
    }
}
