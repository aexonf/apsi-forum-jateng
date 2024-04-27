import { toast } from "sonner";
import React, { useEffect, useState } from "react";
import { cn } from "@/lib/utils";
import { ArrowDownToLine } from "lucide-react";
import Layout from "@/components/elements/layout";
import { Skeleton } from "@/components/ui/skeleton";
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from "@/components/ui/select";
import { Button } from "@/components/ui/button";
import {
    Card,
    CardContent,
    CardFooter,
    CardHeader,
    CardTitle,
} from "@/components/ui/card";
import { Link } from "@inertiajs/inertia-react";
import axios from "axios";

const options = [
    {
        value: "terbaru",
        label: "Terbaru",
    },
    {
        value: "terlama",
        label: "Terlama",
    },
];

export default function Home() {
    const token = localStorage.getItem("token");
    const [loading, setLoading] = useState(false);
    const [data, setData] = useState(null);
    const [selectedOption, setSelectedOption] = useState(options[0].value);
    async function fetchData() {
        setLoading(true);
        await axios
            .get("/api/v1/publication", {
                headers: {
                    Authorization: `Bearer ${token}`,
                },
            })
            .then((res) => {
                setData(res.data.data);
                setLoading(false);
            })
            .catch((err) => {
                toast.error("Ada yang salah.");
                setLoading(false);
            });
    }
    useEffect(() => {
        fetchData();
    }, []);

    async function fetchDataByOption() {
        try {
            setLoading(true);
            const response = await axios.get(
                `/api/v1/publication?sort=${selectedOption}`
            );
            setData(response.data.data);
            setLoading(false);
        } catch (e) {
            toast.error("Ada yang salah.");
        }
    }

    useEffect(() => {
        fetchDataByOption();
    }, [selectedOption]);

    const handleDownload = async (id) => {
        setLoading(true);
        await axios
            .put(
                `/api/v1/publication/${id}`,
                {},
                {
                    headers: {
                        Authorization: `Bearer ${token}`,
                    },
                }
            )
            .then((res) => {
                toast.success("Berhasil mendownload");
                fetchData();
                setLoading(false);
            })
            .catch((e) => {
                toast.error("Ada yang salah");
                setLoading(false);
            });
    };

    return (
        <Layout>
            <div className="flex items-center justify-between gap-3 mt-2 mb-4">
                <h3 className="scroll-m-20 text-xl md:text-2xl font-semibold ">
                    Publikasi
                </h3>

                <Select
                    defaultValue={options[0].value}
                    onValueChange={setSelectedOption}
                >
                    <SelectTrigger
                        aria-label="Select status"
                        className="w-max gap-4"
                    >
                        <SelectValue />
                    </SelectTrigger>
                    <SelectContent>
                        {options.map((option) => (
                            <SelectItem key={option.value} value={option.value}>
                                {option.label}
                            </SelectItem>
                        ))}
                    </SelectContent>
                </Select>
            </div>
            <div className="w-full flex flex-col gap-4">
                {data ? (
                    data.map((item, i) => (
                        <a
                            href={`/storage/${item.file_url}`}
                            download
                            key={i + 1}
                            target="_blank"
                            onClick={() => {
                                if (loading) return;
                                handleDownload(item.id);
                            }}
                            className={cn(
                                "",
                                loading && "pointer-events-none cursor-default"
                            )}
                        >
                            <Card>
                                <CardHeader className="p-4">
                                    <CardTitle className="text-sm md:text-base">
                                        {i + 1}. {item.title}
                                    </CardTitle>
                                </CardHeader>
                                <CardFooter className="w-full justify-end">
                                    <div className="flex items-center text-primary">
                                        <ArrowDownToLine className="size-5" />
                                        <span className="text-xs md:text-sm ml-1">
                                            {item.download_count}
                                        </span>
                                    </div>
                                </CardFooter>
                            </Card>
                        </a>
                    ))
                ) : loading ? (
                    <>
                        <Skeleton className="w-full h-32" />
                        <Skeleton className="w-full h-32" />
                        <Skeleton className="w-full h-32" />
                    </>
                ) : (
                    <div className="text-center mt-40">
                        <h3 className="font-semibold text-3xl">
                            Belum Ada Data
                        </h3>
                    </div>
                )}
            </div>
        </Layout>
    );
}
