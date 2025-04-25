using System;
using System.Data.Common;
using System.Globalization;
using System.Runtime.CompilerServices;
using CsvHelper;

namespace Datensatzgeneration
{
    class Generator
    {
        public class Einkauf
        {
            public int Id { get; set; }
            public int PersonenGruppe { get; set; }
            public int Produkt1 { get; set; }
            public int Produkt2 { get; set; }
            public int Produkt3 { get; set; }
            public int Produkt4 { get; set; }
            public int Produkt5 { get; set; }
            public int Produkt6 { get; set; }
            public int Produkt7 { get; set; }
            public int Produkt8 { get; set; }
            public int Produkt9 { get; set; }
            public int Produkt10 { get; set; }
            public int Produkt11 { get; set; }

            public Einkauf(int Idc, int PersonenGruppec, int Produkt1c, int Produkt2c, int Produkt3c, int Produkt4c, int Produkt5c, int Produkt6c, int Produkt7c, int Produkt8c, int Produkt9c, int Produkt10c, int Produkt11c)
            {
                Id = Idc;
                PersonenGruppe = PersonenGruppec;
                Produkt1 = Produkt1c;
                Produkt2 = Produkt2c;
                Produkt3 = Produkt3c;
                Produkt4 = Produkt4c;
                Produkt5 = Produkt5c;
                Produkt6 = Produkt6c;
                Produkt7 = Produkt7c;
                Produkt8 = Produkt8c;
                Produkt9 = Produkt9c;
                Produkt10 = Produkt10c;
                Produkt11 = Produkt11c;
            }
        }
        static void Main()
        {
            int[] pG1 = [15, 33, 25, 13, 15, 10, 22, 18, 32, 27, 17];
            int[] pG2 = [23, 34, 27, 17, 18, 12, 25, 20, 35, 29, 18];
            int[] pG3 = [31, 23, 23, 25, 23, 15, 28, 30, 38, 33, 24];
            int[] pG4 = [35, 11, 15, 38, 30, 10, 34, 35, 40, 37, 30];
            Random prozent = new Random();
            int zzahl = 0;
            int pGruppe = 0;
            int Einkauf1 = 0;
            int Einkauf2 = 0;
            int Einkauf3 = 0;
            int Einkauf4 = 0;
            int Einkauf5 = 0;
            int Einkauf6 = 0;
            int Einkauf7 = 0;
            int Einkauf8 = 0;
            int Einkauf9 = 0;
            int Einkauf10 = 0;
            int Einkauf11 = 0;
            var Einkaufsliste = new List<Einkauf>();
            static int Einkaufzahl(int zufallzahl, int prozenzahl)
            {
                if (zufallzahl <= prozenzahl)
                {
                    return 1;
                }
                else
                {
                    return 0;
                }
            }
            for (int i = 1; i < 100000; i++)
            {
                zzahl = prozent.Next(1, 4);
                switch (zzahl)
                {
                    case 1:
                        pGruppe = 1;
                        zzahl = prozent.Next(1, 100);
                        Einkauf1 = Einkaufzahl(zzahl, pG1[0]);
                        zzahl = prozent.Next(1, 100);
                        Einkauf2 = Einkaufzahl(zzahl, pG1[1]);
                        zzahl = prozent.Next(1, 100);
                        Einkauf3 = Einkaufzahl(zzahl, pG1[2]);
                        zzahl = prozent.Next(1, 100);
                        Einkauf4 = Einkaufzahl(zzahl, pG1[3]);
                        zzahl = prozent.Next(1, 100);
                        Einkauf5 = Einkaufzahl(zzahl, pG1[4]);
                        zzahl = prozent.Next(1, 100);
                        Einkauf6 = Einkaufzahl(zzahl, pG1[5]);
                        zzahl = prozent.Next(1, 100);
                        Einkauf7 = Einkaufzahl(zzahl, pG1[6]);
                        zzahl = prozent.Next(1, 100);
                        Einkauf8 = Einkaufzahl(zzahl, pG1[7]);
                        zzahl = prozent.Next(1, 100);
                        Einkauf9 = Einkaufzahl(zzahl, pG1[8]);
                        zzahl = prozent.Next(1, 100);
                        Einkauf10 = Einkaufzahl(zzahl, pG1[9]);
                        zzahl = prozent.Next(1, 100);
                        Einkauf11 = Einkaufzahl(zzahl, pG1[10]);
                        break;
                    case 2:
                        pGruppe = 2;
                        zzahl = prozent.Next(1, 100);
                        Einkauf1 = Einkaufzahl(zzahl, pG2[0]);
                        zzahl = prozent.Next(1, 100);
                        Einkauf2 = Einkaufzahl(zzahl, pG2[1]);
                        zzahl = prozent.Next(1, 100);
                        Einkauf3 = Einkaufzahl(zzahl, pG2[2]);
                        zzahl = prozent.Next(1, 100);
                        Einkauf4 = Einkaufzahl(zzahl, pG2[3]);
                        zzahl = prozent.Next(1, 100);
                        Einkauf5 = Einkaufzahl(zzahl, pG2[4]);
                        zzahl = prozent.Next(1, 100);
                        Einkauf6 = Einkaufzahl(zzahl, pG2[5]);
                        zzahl = prozent.Next(1, 100);
                        Einkauf7 = Einkaufzahl(zzahl, pG2[6]);
                        zzahl = prozent.Next(1, 100);
                        Einkauf8 = Einkaufzahl(zzahl, pG2[7]);
                        zzahl = prozent.Next(1, 100);
                        Einkauf9 = Einkaufzahl(zzahl, pG2[8]);
                        zzahl = prozent.Next(1, 100);
                        Einkauf10 = Einkaufzahl(zzahl, pG2[9]);
                        zzahl = prozent.Next(1, 100);
                        Einkauf11 = Einkaufzahl(zzahl, pG2[10]);
                        break;
                    case 3:
                        pGruppe = 3;
                        zzahl = prozent.Next(1, 100);
                        Einkauf1 = Einkaufzahl(zzahl, pG3[0]);
                        zzahl = prozent.Next(1, 100);
                        Einkauf2 = Einkaufzahl(zzahl, pG3[1]);
                        zzahl = prozent.Next(1, 100);
                        Einkauf3 = Einkaufzahl(zzahl, pG3[2]);
                        zzahl = prozent.Next(1, 100);
                        Einkauf4 = Einkaufzahl(zzahl, pG3[3]);
                        zzahl = prozent.Next(1, 100);
                        Einkauf5 = Einkaufzahl(zzahl, pG3[4]);
                        zzahl = prozent.Next(1, 100);
                        Einkauf6 = Einkaufzahl(zzahl, pG3[5]);
                        zzahl = prozent.Next(1, 100);
                        Einkauf7 = Einkaufzahl(zzahl, pG3[6]);
                        zzahl = prozent.Next(1, 100);
                        Einkauf8 = Einkaufzahl(zzahl, pG3[7]);
                        zzahl = prozent.Next(1, 100);
                        Einkauf9 = Einkaufzahl(zzahl, pG3[8]);
                        zzahl = prozent.Next(1, 100);
                        Einkauf10 = Einkaufzahl(zzahl, pG3[9]);
                        zzahl = prozent.Next(1, 100);
                        Einkauf11 = Einkaufzahl(zzahl, pG3[10]);
                        break;
                    case 4:
                        pGruppe = 4;
                        zzahl = prozent.Next(1, 100);
                        Einkauf1 = Einkaufzahl(zzahl, pG4[0]);
                        zzahl = prozent.Next(1, 100);
                        Einkauf2 = Einkaufzahl(zzahl, pG4[1]);
                        zzahl = prozent.Next(1, 100);
                        Einkauf3 = Einkaufzahl(zzahl, pG4[2]);
                        zzahl = prozent.Next(1, 100);
                        Einkauf4 = Einkaufzahl(zzahl, pG4[3]);
                        zzahl = prozent.Next(1, 100);
                        Einkauf5 = Einkaufzahl(zzahl, pG4[4]);
                        zzahl = prozent.Next(1, 100);
                        Einkauf6 = Einkaufzahl(zzahl, pG4[5]);
                        zzahl = prozent.Next(1, 100);
                        Einkauf7 = Einkaufzahl(zzahl, pG4[6]);
                        zzahl = prozent.Next(1, 100);
                        Einkauf8 = Einkaufzahl(zzahl, pG4[7]);
                        zzahl = prozent.Next(1, 100);
                        Einkauf9 = Einkaufzahl(zzahl, pG4[8]);
                        zzahl = prozent.Next(1, 100);
                        Einkauf10 = Einkaufzahl(zzahl, pG4[9]);
                        zzahl = prozent.Next(1, 100);
                        Einkauf11 = Einkaufzahl(zzahl, pG4[10]);
                        break;
                    default:

                        break;
                }
                if(Einkauf1 == 0 & Einkauf2 == 0 & Einkauf3 == 0 & Einkauf4 == 0 & Einkauf5 == 0 & Einkauf6 == 0 & Einkauf7 == 0 & Einkauf8 == 0 & Einkauf9 == 0 & Einkauf10 == 0 & Einkauf11 == 0)
                {

                }else
                {
                Einkaufsliste.Add(new Einkauf(i, pGruppe, Einkauf1, Einkauf2, Einkauf3, Einkauf4, Einkauf5, Einkauf6, Einkauf7, Einkauf8, Einkauf9, Einkauf10, Einkauf11));
                }
            }
            using (var writer = new StreamWriter("Datensatz.csv"))
            using (var csv = new CsvWriter(writer, CultureInfo.InvariantCulture))
            {
                csv.WriteRecords(Einkaufsliste);
            }
        }
    }

}


